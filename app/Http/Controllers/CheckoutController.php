<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $carts->sum(function ($cart) {
            return $cart->subtotal;
        });

        // Contoh ongkos kirim flat
        $shippingCost = 50000;
        $total = $subtotal + $shippingCost;

        return view('checkout.index', compact('carts', 'subtotal', 'shippingCost', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone'            => 'required|string|max:20',
            'city'             => 'required|string',
            'province'         => 'required|string',
            'postal_code'      => 'required|string',
            'payment_method'   => 'required|in:transfer_bank,cod',
            'notes'            => 'nullable|string',
        ]);

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Keranjang kosong.');
        }

        // Cek stok kembali
        foreach ($carts as $cart) {
            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $cart->product->name . ' tidak mencukupi.');
            }
        }

        try {
            DB::beginTransaction();

            $subtotal = $carts->sum(function ($cart) {
                return $cart->subtotal;
            });
            $shippingCost = 50000;
            $totalPrice = $subtotal + $shippingCost;

            $order = Order::create([
                'order_code'       => Order::generateCode(),
                'user_id'          => Auth::id(),
                'shipping_address' => $request->shipping_address,
                'phone'            => $request->phone,
                'city'             => $request->city,
                'province'         => $request->province,
                'postal_code'      => $request->postal_code,
                'payment_method'   => $request->payment_method,
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total_price'      => $totalPrice,
                'status'           => 'pending',
                'notes'            => $request->notes,
            ]);

            foreach ($carts as $cart) {
                // Ambil data produk terbaru untuk menghindari race condition
                $product = $cart->product;
                
                // Jika ada variasi, kurangi stok variasinya
                if ($cart->variation && is_array($product->variations)) {
                    $variations = $product->variations;
                    foreach ($variations as &$var) {
                        if ($var['name'] === $cart->variation) {
                            $var['stock'] = max(0, $var['stock'] - $cart->quantity);
                            break;
                        }
                    }
                    $product->variations = $variations;
                    $product->save();
                }

                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'variation'    => $cart->variation,
                    'price'        => $cart->price ?? $cart->product->price,
                    'quantity'     => $cart->quantity,
                    'subtotal'     => $cart->subtotal,
                ]);

                // Kurangi stok total produk
                $product->decrement('stock', $cart->quantity);
            }

            // Kosongkan keranjang
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Push notifikasi ke Firebase
            try {
                $firebaseService = new \App\Services\FirebaseService();
                $firebaseService->pushOrderNotification($order);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal push firebase: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
