<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;

// Register tidak pakai user_id di URL
Route::post('trebel/register', [AuthController::class, 'register']); // 🔥 tanpa {user_id}

// Login tetap pakai user_id di URL
Route::prefix('{user_id}/trebel')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::patch('profile', [AuthController::class, 'updateProfile']);
        Route::delete('delete', [AuthController::class, 'deleteAccount']);
    });
});

// OTP Routes tetap
Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'sendOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
    Route::post('resend', [OtpController::class, 'resendOtp']);
});

// Logout route (global)
Route::post('logout', [AuthController::class, 'logout']);

