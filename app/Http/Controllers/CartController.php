<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        $total = $carts->sum(function ($cart) {
            // Gunakan harga dari cart jika ada, jika tidak gunakan harga produk
            $price = $cart->price ?? $cart->product->price;
            return $price * $cart->quantity;
        });

        return view('cart.index', compact('carts', 'total'));
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity'   => 'required|integer|min:1',
                'variation'  => 'nullable|string',
            ]);

            $product = Product::findOrFail($request->product_id);
        
        // Cari harga dan stok (Variasi atau Satuan)
        $price = $product->price;
        $availableStock = $product->stock;
        
        if ($request->variation) {
            foreach ($product->variations_array as $var) {
                if (trim($var['name']) === trim($request->variation)) {
                    $price = $var['price'];
                    $availableStock = $var['stock'];
                    break;
                }
            }
        }

        // Cek apakah produk sudah ada di keranjang
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->where('variation', $request->variation)
                    ->first();

            if ($cart) {
                $newQuantity = $cart->quantity + $request->quantity;
                if ($availableStock < $newQuantity) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Maaf, total stok tidak mencukupi.'], 422);
                    }
                    return back()->with('error', 'Maaf, total stok tidak mencukupi. Sisa stok ' . ($request->variation ? 'variasi ini' : 'produk ini') . ': ' . $availableStock);
                }
                $cart->update(['quantity' => $newQuantity]);
            } else {
                if ($availableStock < $request->quantity) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Maaf, stok tidak mencukupi.'], 422);
                    }
                    return back()->with('error', 'Maaf, stok tidak mencukupi. Sisa stok: ' . $availableStock);
                }
                Cart::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $product->id,
                    'variation'  => $request->variation,
                    'price'      => $price, // Simpan harga variasi
                    'quantity'   => $request->quantity,
                ]);
            }

            if ($request->expectsJson()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang!',
                    'cart_count' => $cartCount
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui!');
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('success', 'Keranjang belanja dikosongkan.');
    }
}
