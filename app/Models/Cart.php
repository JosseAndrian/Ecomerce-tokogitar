<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'variation', 'quantity', 'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        $price = $this->price ?? $this->product->price;
        return $price * $this->quantity;
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->price ?? $this->product->price;
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    public function getImageUrlAttribute()
    {
        if ($this->variation && is_array($this->product->variations)) {
            foreach ($this->product->variations as $var) {
                if ($var['name'] === $this->variation && !empty($var['image'])) {
                    return asset('storage/' . $var['image']);
                }
            }
        }
        return $this->product->image_url;
    }
}
