<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'proof_image', 'status',
        'bank_name', 'account_number', 'account_name',
        'notes', 'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getProofImageUrlAttribute(): ?string
    {
        if ($this->proof_image && \Storage::disk('public')->exists($this->proof_image)) {
            return asset('storage/' . $this->proof_image);
        }
        return null;
    }
}
