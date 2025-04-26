<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp',
        'otp_token', // ✅ Tambahkan ini!
        'expired_at',
        'is_verified',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}
