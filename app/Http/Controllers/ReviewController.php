<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        // Cek apakah order milik user dan sudah selesai
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->whereIn('status', ['paid', 'shipped', 'completed'])
                      ->firstOrFail();

        // Cek apakah produk ada di order tersebut
        $hasProduct = $order->orderDetails()->where('product_id', $request->product_id)->exists();
        if (!$hasProduct) {
            return back()->with('error', 'Produk tidak ditemukan dalam pesanan ini.');
        }

        // Cek apakah sudah pernah memberi ulasan
        $existing = Review::where('user_id', Auth::id())
                          ->where('order_id', $request->order_id)
                          ->where('product_id', $request->product_id)
                          ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini di pesanan ini.');
        }

        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
