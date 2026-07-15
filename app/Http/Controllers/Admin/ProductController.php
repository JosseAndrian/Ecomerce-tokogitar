<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'nullable|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses Variasi Terlebih Dahulu
        $variations = [];
        $totalStock = 0;
        if ($request->has('var_names')) {
            foreach ($request->var_names as $index => $name) {
                if ($name) {
                    $varImagePath = null;
                    if ($request->hasFile("var_images.{$index}")) {
                        $varImagePath = $request->file("var_images.{$index}")->store('products/variations', 'public');
                    }
                    
                    $vPrice = $request->var_prices[$index] ?? $request->price;
                    $vStock = $request->var_stocks[$index] ?? 0;
                    $totalStock += (int)$vStock;

                    $variations[] = [
                        'name'  => $name,
                        'price' => (float)$vPrice,
                        'stock' => (int)$vStock,
                        'image' => $varImagePath,
                    ];
                }
            }
        }

        // Jika ada variasi, gunakan total stok variasi, jika tidak gunakan stok utama
        $mainStock = (count($variations) > 0) ? $totalStock : $request->stock;

        // Ambil Harga Utama (jika kosong, ambil dari variasi pertama)
        $mainPrice = $request->price;
        if (is_null($mainPrice) && count($variations) > 0) {
            $mainPrice = $variations[0]['price'];
        }

        // Ambil Gambar Utama (jika kosong, ambil dari variasi pertama)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif (count($variations) > 0 && !is_null($variations[0]['image'])) {
            $imagePath = $variations[0]['image'];
        }

        // Validasi Akhir
        if (is_null($mainPrice)) {
            return back()->withInput()->withErrors(['price' => 'Harga harus diisi.']);
        }
        if (is_null($imagePath)) {
            return back()->withInput()->withErrors(['image' => 'Gambar harus diisi.']);
        }

        Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'variations'  => $variations,
            'price'       => $mainPrice,
            'stock'       => $mainStock,
            'is_featured' => $request->has('is_featured'),
            'is_active'   => $request->has('is_active'),
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'nullable|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses Variasi
        $variations = [];
        $totalStock = 0;
        if ($request->has('var_names')) {
            foreach ($request->var_names as $index => $name) {
                if ($name) {
                    $varImagePath = $request->var_existing_images[$index] ?? null;
                    if ($request->hasFile("var_images.{$index}")) {
                        if ($varImagePath && Storage::disk('public')->exists($varImagePath)) {
                            Storage::disk('public')->delete($varImagePath);
                        }
                        $varImagePath = $request->file("var_images.{$index}")->store('products/variations', 'public');
                    }
                    
                    $vPrice = $request->var_prices[$index] ?? $request->price ?? $product->price;
                    $vStock = $request->var_stocks[$index] ?? 0;
                    $totalStock += (int)$vStock;

                    $variations[] = [
                        'name'  => $name,
                        'price' => (float)$vPrice,
                        'stock' => (int)$vStock,
                        'image' => $varImagePath,
                    ];
                }
            }
        }

        $mainStock = (count($variations) > 0) ? $totalStock : $request->stock;

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'variations'  => $variations,
            'price'       => $request->price ?? $product->price,
            'stock'       => $mainStock,
            'is_featured' => $request->has('is_featured'),
            'is_active'   => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Hapus semua relasi terkait
        $product->orderDetails()->delete();
        $product->cartItems()->delete();
        $product->reviews()->delete();

        // Hapus gambar variasi
        if ($product->variations) {
            foreach ($product->variations as $variation) {
                if (isset($variation['image']) && $variation['image'] && Storage::disk('public')->exists($variation['image'])) {
                    Storage::disk('public')->delete($variation['image']);
                }
            }
        }

        // Hapus gambar utama
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
