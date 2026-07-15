<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirebaseService
{
    private $credentials;
    private $databaseUrl;

    public function __construct()
    {
        $path = storage_path('app/firebase-credentials.json');
        if (file_exists($path)) {
            $this->credentials = json_decode(file_get_contents($path), true);
        } else if (env('FIREBASE_CREDENTIALS_JSON')) {
            // Berguna untuk deployment di Railway / Cloud
            $this->credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);
        }
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL'), '/');
    }

    /**
     * Generate Custom Token for Client-side Firebase Auth (Browser)
     */
    public function generateCustomToken(string $uid, array $claims = []): ?string
    {
        if (!$this->credentials || !isset($this->credentials['private_key'])) {
            Log::error('Firebase credentials not found or invalid.');
            return null;
        }

        $now = $this->getGoogleTime();
        $clientEmail = $this->credentials['client_email'];

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit',
            'iat' => $now - 10,
            'exp' => $now + 3600,
            'uid' => $uid,
        ];

        if (!empty($claims)) {
            $payload['claims'] = $claims;
        }

        return $this->signJwt($header, $payload);
    }

    /**
     * Ambil waktu nyata dari header HTTP Google untuk menghindari clock skew
     */
    private function getGoogleTime(): int
    {
        try {
            $timeResponse = Http::timeout(5)->head('https://www.google.com');
            $dateStr = $timeResponse->header('Date');
            if ($dateStr) {
                $parsed = strtotime($dateStr);
                if ($parsed) return $parsed;
            }
        } catch (\Exception $e) {
            // fallback
        }
        return time();
    }

    /**
     * Get OAuth2 Access Token for Server-side REST API calls
     */
    public function getAccessToken(): ?string
    {
        if (!$this->credentials) {
            return null;
        }

        return Cache::remember('firebase_access_token', 3500, function () {
            $now = $this->getGoogleTime();

            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT',
            ];
            
            $payload = [
                'iss' => $this->credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/firebase.database',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now - 10,
            ];

            $jwt = $this->signJwt($header, $payload);

            if (!$jwt) return null;

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Failed to get Firebase access token: ' . $response->body());
            return null;
        });
    }

    private function signJwt(array $header, array $payload): ?string
    {
        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $dataToSign = $base64UrlHeader . '.' . $base64UrlPayload;
        $signature = '';

        $success = openssl_sign(
            $dataToSign,
            $signature,
            $this->credentials['private_key'],
            OPENSSL_ALGO_SHA256
        );

        if (!$success) {
            Log::error('Failed to sign JWT with openssl_sign.');
            return null;
        }

        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $dataToSign . '.' . $base64UrlSignature;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Push new order notification to Firebase Realtime Database
     */
    public function pushOrderNotification($order): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('Cannot push to Firebase without access token.');
            return false;
        }

        $newData = [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'user_id' => $order->user_id,
            'user_name' => $order->user->name ?? 'Customer',
            'total_price' => $order->total_price,
            'status' => $order->status,
            'created_at' => now()->toDateTimeString(),
            'read' => false,
        ];

        try {
            // Kita push ke node /orders
            $url = $this->databaseUrl . '/orders.json?access_token=' . $accessToken;
            $response = Http::post($url, $newData);

            if ($response->successful()) {
                Log::info('Firebase push OK: ' . $response->body());
                return true;
            } else {
                Log::error('Firebase push FAILED: status=' . $response->status() . ' body=' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Firebase Push Order Error: ' . $e->getMessage());
            return false;
        }
    }
}
