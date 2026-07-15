<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);
                       
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderDetails.product', 'payment']);
        
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan.');
        }

        try {
            \DB::beginTransaction();

            // Kembalikan stok
            foreach ($order->orderDetails as $item) {
                $product = $item->product;
                
                if ($product) {
                    // Kembalikan stok variasi jika ada
                    if ($item->variation && is_array($product->variations)) {
                        $variations = $product->variations;
                        foreach ($variations as &$var) {
                            if ($var['name'] === $item->variation) {
                                $var['stock'] += $item->quantity;
                                break;
                            }
                        }
                        $product->variations = $variations;
                        $product->save();
                    }

                    // Kembalikan stok total
                    $product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            \DB::commit();

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
