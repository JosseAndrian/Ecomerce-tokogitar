<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories      = Category::withCount('products')->get();
        $latestProducts  = Product::with('category')
                                  ->where('is_active', true)
                                  ->latest()
                                  ->take(8)
                                  ->get();
        $popularProducts = Product::with('category')
                                  ->where('is_active', true)
                                  ->orderByDesc('sold_count')
                                  ->take(8)
                                  ->get();
        $featuredProducts = Product::with('category')
                                   ->where('is_active', true)
                                   ->where('is_featured', true)
                                   ->take(4)
                                   ->get();

        return view('home.index', compact('categories', 'latestProducts', 'popularProducts', 'featuredProducts'));
    }
}
