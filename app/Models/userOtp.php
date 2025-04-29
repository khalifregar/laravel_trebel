<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',    // ✅ Tambah field user_id
        'phone',      // ✅ Tetap ada phone buat kirim OTP
        'otp',
        'otp_token',
        'expired_at',
        'is_verified',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}
