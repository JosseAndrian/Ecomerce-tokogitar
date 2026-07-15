<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category_id', 'description', 'variations', 'price',
        'stock', 'image', 'is_featured', 'is_active', 'sold_count',
    ];

    protected $casts = [
        'variations'  => 'array',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . uniqid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getVariationsArrayAttribute()
    {
        $variations = $this->variations;
        
        if (is_string($variations)) {
            $variations = json_decode($variations, true);
        }

        if (!is_array($variations)) {
            $variations = [];
        }
        
        // Pastikan harga terformat ada di sini untuk JS
        foreach ($variations as &$v) {
            $v['formatted_price'] = 'Rp ' . number_format($v['price'] ?? 0, 0, ',', '.');
        }
        
        return $variations;
    }

    public function getSoldCountAttribute()
    {
        // Hitung total terjual dari pesanan yang sudah dibayar/selesai
        return \App\Models\OrderDetail::where('product_id', $this->id)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['paid', 'completed', 'shipped']);
            })->sum('quantity');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            if (\Storage::disk('public')->exists($this->image)) {
                return asset('storage/' . $this->image);
            }
        }

        $categoryName = $this->category ? strtolower($this->category->name) : '';

        if (str_contains($categoryName, 'akustik')) {
            return 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'elektrik')) {
            return 'https://images.unsplash.com/photo-1564186763535-ebb21ec52744?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'bass')) {
            return 'https://images.unsplash.com/photo-1583763273187-5784918e9573?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'drum') || str_contains($categoryName, 'perkusi')) {
            return 'https://images.unsplash.com/photo-1547427650-8547313dacae?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'keyboard') || str_contains($categoryName, 'piano')) {
            return 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'aksesoris')) {
            return 'https://images.unsplash.com/photo-1618609378039-b572f64c5b42?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'biola') || str_contains($categoryName, 'violin')) {
            return 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'mic') || str_contains($categoryName, 'micro')) {
            return 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?q=80&w=600&auto=format&fit=crop';
        }
        if (str_contains($categoryName, 'soundcard') || str_contains($categoryName, 'mixer')) {
            return 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?q=80&w=600&auto=format&fit=crop';
        }

        return 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80&w=600&auto=format&fit=crop';
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function is_in_stock(): bool
    {
        return $this->stock > 0;
    }
}
