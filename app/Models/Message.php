<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'is_from_admin', 'is_read', 'is_bot'];

    protected $casts = [
        'is_from_admin' => 'boolean',
        'is_read' => 'boolean',
        'is_bot' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
