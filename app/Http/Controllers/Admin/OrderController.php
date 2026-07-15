<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // SINKRONISASI: Jika status pesanan diubah ke paid/processing, verifikasi pembayarannya juga
        if (in_array($request->status, ['paid', 'processing']) && $order->payment) {
            $order->payment->update([
                'status' => 'verified',
                'verified_at' => now()
            ]);
        }

        // Jika pesanan selesai, tambahkan ke sold_count produk
        if ($request->status === 'completed') {
            foreach ($order->orderDetails as $detail) {
                if ($detail->product) {
                    $detail->product->increment('sold_count', $detail->quantity);
                }
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        if (!$order->payment) {
            return back()->with('error', 'Pembayaran belum dilakukan.');
        }

        $request->validate([
            'payment_status' => 'required|in:verified,rejected',
            'order_status'   => 'required|in:paid,processing,cancelled'
        ]);

        $order->payment->update([
            'status'      => $request->payment_status,
            'verified_at' => $request->payment_status === 'verified' ? now() : null,
        ]);

        $order->update([
            'status' => $request->order_status
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
