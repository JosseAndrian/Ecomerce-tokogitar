<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'variation', 'price', 'quantity', 'subtotal',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
