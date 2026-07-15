<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // USER SIDE
    public function userIndex()
    {
        $messages = Message::where('user_id', Auth::id())
                          ->orderBy('created_at', 'asc')
                          ->get();
        
        // Mark admin messages as read
        Message::where('user_id', Auth::id())
               ->where('is_from_admin', true)
               ->update(['is_read' => true]);

        return view('chat.user', compact('messages'));
    }

    public function userSend(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => false
        ]);

        // Cek apakah admin (manusia) telah membalas dalam 12 jam terakhir
        $lastAdminMsg = Message::where('user_id', Auth::id())
            ->where('is_from_admin', true)
            ->orderBy('created_at', 'desc')
            ->first();
            
        $adminHasTakenOver = $lastAdminMsg && !$lastAdminMsg->is_bot && $lastAdminMsg->created_at->diffInHours(now()) < 12;

        if (!$adminHasTakenOver) {
            $this->saveBotReply(Auth::id(), $this->buildReply($request->message));
        }

        return back();
    }

    // AI CHATBOT WIDGET (Floating Widget on Frontend)
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $reply = $this->buildReply($request->message);
        return response()->json(['response' => $reply]);
    }

    // =========================================================
    //  SHARED BOT LOGIC
    // =========================================================

    private function buildReply(string $rawMessage): string
    {
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey) || $apiKey === 'your_api_key_here') {
            return "Maaf, sistem AI cerdas belum aktif karena `GEMINI_API_KEY` belum dikonfigurasi di file `.env`.\nSilakan hubungi admin toko untuk bantuan manual.";
        }

        try {
            // Ambil sampel produk aktif sebagai konteks untuk AI
            $products = Product::where('is_active', true)->inRandomOrder()->take(20)->get()->map(function($p) {
                return "- {$p->name} ({$p->category->name}) : Rp " . number_format($p->price, 0, ',', '.');
            })->implode("\n");

            $systemPrompt = "Anda adalah MusikBot, AI Customer Service untuk toko alat musik bernama 'Musik Store'. 
Lokasi toko di Jl. Margonda Raya No. 100, Depok.
Jam Buka: Senin-Jumat (09.00-21.00), Sabtu-Minggu (09.00-22.00).
Kontak CS: +62 812-3456-7890 (WhatsApp) / cs@musikstore.com.
Anda ramah, sopan, dan menggunakan bahasa Indonesia yang santai tapi profesional. Selalu gunakan emoji yang relevan.

Berikut adalah beberapa contoh produk yang kami jual saat ini (hanya sebagian dari katalog):
" . $products . "

Aturan:
1. Jawab pertanyaan user hanya berdasarkan konteks toko musik dan produk di atas.
2. Jika ditanya rekomendasi produk, berikan rekomendasi dari daftar di atas. Jika tidak ada di daftar, beri tahu bahwa katalog lengkap bisa dicek di menu 'Produk' website.
3. Cara Pesan: Tambah produk ke keranjang -> Checkout -> Bayar via Transfer Bank (BCA/Mandiri) -> Upload Bukti Pembayaran -> Admin konfirmasi.
4. Pengiriman: JNE, J&T, SiCepat. Jawa 1-2 hari, Luar Jawa 3-5 hari.
5. Garansi: Retur berlaku 3 hari sejak barang diterima (sertakan bukti foto).
6. Jawab dengan format Markdown agar rapi (bold, bullet points).
7. Jangan pernah membuat (mengarang) nama produk yang tidak ada di daftar. Jangan mengarang nomor rekening.";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemPrompt]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $rawMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($data['candidates'][0]['content']['parts'][0]['text']);
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
        }

        // ==========================================
        // FALLBACK: JIKA API GEMINI GAGAL / KEY SALAH
        // Kita gunakan algoritma pencarian internal pintar
        // ==========================================
        
        $msg = strtolower(trim($rawMessage));
        $words = explode(' ', preg_replace('/[^a-z0-9 ]/', '', $msg));
        $ignoreWords = ['saya', 'sedang', 'mencari', 'ingin', 'tahu', 'ada', 'gak', 'jual', 'cari', 'mau', 'beli', 'harga', 'berapa', 'tanya', 'dong', 'min', 'bot', 'apakah', 'bang', 'mas', 'kak', 'alat', 'musik', 'untuk', 'yang', 'dan', 'di'];
        $keywords = array_diff($words, $ignoreWords);
        
        $foundProducts = collect();

        // 1. Coba cari berdasarkan kategori/nama untuk setiap keyword penting
        foreach ($keywords as $kw) {
            if (strlen($kw) < 3) continue; // skip kata terlalu pendek
            
            $products = Product::where('name', 'like', '%' . $kw . '%')
                ->orWhereHas('category', function($q) use ($kw) {
                    $q->where('name', 'like', '%' . $kw . '%');
                })
                ->orWhere('description', 'like', '%' . $kw . '%')
                ->take(5)
                ->get();
                
            $foundProducts = $foundProducts->merge($products);
        }

        // Hapus duplikat dan ambil 5 teratas
        $foundProducts = $foundProducts->unique('id')->take(5);

        if ($foundProducts->count() > 0) {
            $list = $foundProducts->map(function($p) { 
                return "- **{$p->name}** (Rp " . number_format($p->price, 0, ',', '.') . ")"; 
            })->implode("\n");
            
            return "🔍 Berdasarkan pertanyaan Anda, kami memiliki beberapa produk yang mungkin cocok:\n\n{$list}\n\nSilakan cek detail lengkapnya di katalog website kami! 🛒";
        }

        return "Terima kasih telah menghubungi **Musik Store** 🤖\nMaaf, sistem AI cerdas kami saat ini sedang offline, dan saya belum menemukan produk spesifik yang pas untuk pertanyaan Anda di database lokal.\n\nMohon ditunggu ya, admin manusia kami akan segera membalas pesan Anda! 😊";
    }

    /**
     * Check if message contains any of the given keywords.
     */
    private function matches(string $msg, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($msg, $kw)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Format a product collection as a readable list.
     */
    private function formatProductList($products): string
    {
        if ($products->isEmpty()) {
            return "Stok sedang kosong. Hubungi admin untuk informasi lebih lanjut.";
        }
        return $products->map(function ($p, $i) {
            $price = 'Rp ' . number_format($p->price, 0, ',', '.');
            $url   = url('/shop/' . $p->slug);
            return ($i + 1) . ". <a href=\"{$url}\" class=\"bot-product-link\">{$p->name}</a> — <strong>{$price}</strong>";
        })->implode("\n");
    }

    /**
     * Save an auto-bot reply message to DB.
     */
    private function saveBotReply(int $userId, string $reply): void
    {
        Message::create([
            'user_id'       => $userId,
            'message'       => $reply,
            'is_from_admin' => true,
            'is_read'       => false,
            'is_bot'        => true,
        ]);
    }

    // =========================================================
    //  ADMIN SIDE
    // =========================================================

    public function adminIndex()
    {
        $chats = User::whereHas('messages')
                    ->withCount(['messages as unread_count' => function($query) {
                        $query->where('is_from_admin', false)->where('is_read', false);
                    }])
                    ->get()
                    ->sortByDesc(function($user) {
                        return $user->messages->max('created_at');
                    });

        return view('admin.chat.index', compact('chats'));
    }

    public function adminShow(User $user)
    {
        $messages = Message::where('user_id', $user->id)
                          ->orderBy('created_at', 'asc')
                          ->get();

        Message::where('user_id', $user->id)
               ->where('is_from_admin', false)
               ->update(['is_read' => true]);

        return view('admin.chat.show', compact('user', 'messages'));
    }

    public function adminSend(Request $request, User $user)
    {
        $request->validate(['message' => 'required|string']);

        Message::create([
            'user_id'       => $user->id,
            'message'       => $request->message,
            'is_from_admin' => true
        ]);

        return back();
    }
}
