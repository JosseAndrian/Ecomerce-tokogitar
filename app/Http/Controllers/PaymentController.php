<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending' && !$order->payment) {
            return redirect()->route('orders.show', $order->id)->with('error', 'Pesanan ini tidak dapat diunggah pembayarannya.');
        }

        return view('payment.show', compact('order'));
    }

    public function upload(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'proof_image'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_name'      => 'required|string',
            'account_name'   => 'required|string',
            'account_number' => 'required|string',
        ]);

        $imagePath = $request->file('proof_image')->store('payments', 'public');

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'proof_image'    => $imagePath,
                'bank_name'      => $request->bank_name,
                'account_name'   => $request->account_name,
                'account_number' => $request->account_number,
                'status'         => 'pending',
            ]
        );

        $order->update(['status' => 'paid']);

        return redirect()->route('orders.show', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah. Kami akan segera memverifikasinya.');
    }
}
