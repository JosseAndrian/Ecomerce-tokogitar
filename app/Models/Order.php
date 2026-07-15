<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id', 'shipping_address', 'phone',
        'city', 'province', 'postal_code', 'payment_method',
        'subtotal', 'shipping_cost', 'total_price', 'status', 'notes',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_price'   => 'decimal:2',
    ];

    const STATUS_LABELS = [
        'pending'    => ['label' => 'Menunggu Pembayaran', 'badge' => 'warning'],
        'paid'       => ['label' => 'Pembayaran Diterima',  'badge' => 'info'],
        'processing' => ['label' => 'Diproses',             'badge' => 'primary'],
        'shipped'    => ['label' => 'Dikirim',              'badge' => 'secondary'],
        'completed'  => ['label' => 'Selesai',              'badge' => 'success'],
        'cancelled'  => ['label' => 'Dibatalkan',           'badge' => 'danger'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['badge'] ?? 'secondary';
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public static function generateCode(): string
    {
        do {
            $code = 'ORD-' . strtoupper(date('Ymd')) . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
