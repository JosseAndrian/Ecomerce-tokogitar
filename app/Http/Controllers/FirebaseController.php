<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FirebaseController extends Controller
{
    /**
     * URL dasar Firebase Realtime Database (dari .env)
     * Contoh: https://your-project-default-rtdb.firebaseio.com
     */
    private function baseUrl(): string
    {
        return rtrim(env('FIREBASE_DATABASE_URL'), '/');
    }

    /**
     * Simpan data ke Firebase Realtime Database via REST API
     * POST /api/firebase/store
     */
    public function storeData(Request $request)
    {
        // Contoh data yang akan dikirim (pesanan alat musik)
        $newData = [
            'product_name' => 'Gitar Akustik Yamaha',
            'price'        => 1500000,
            'status'       => 'pending',
            'created_at'   => now()->toDateTimeString(),
        ];

        try {
            // Firebase REST: POST ke /orders.json akan membuat ID unik otomatis
            $response = Http::post($this->baseUrl() . '/orders.json', $newData);

            if ($response->failed()) {
                throw new \Exception('Firebase mengembalikan error: ' . $response->body());
            }

            $firebaseId = $response->json('name'); // ID unik dari Firebase

            return response()->json([
                'success'     => true,
                'message'     => 'Data berhasil disimpan ke Firebase!',
                'firebase_id' => $firebaseId,
                'data'        => $newData,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil semua data dari Firebase Realtime Database via REST API
     * GET /api/firebase/get
     */
    public function getData()
    {
        try {
            // Firebase REST: GET ke /orders.json mengambil semua data di node orders
            $response = Http::get($this->baseUrl() . '/orders.json');

            if ($response->failed()) {
                throw new \Exception('Firebase mengembalikan error: ' . $response->body());
            }

            return response()->json([
                'success' => true,
                'data'    => $response->json(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Men-generate Firebase Custom Token untuk user yang login
     * GET /api/firebase/token
     */
    public function getToken(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $firebaseService = new \App\Services\FirebaseService();
        
        // Anda bisa menambahkan claims spesifik seperti role admin
        $claims = [
            'role' => $user->role ?? 'user'
        ];

        $token = $firebaseService->generateCustomToken((string) $user->id, $claims);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate Firebase token.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}

