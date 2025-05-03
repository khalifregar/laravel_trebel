<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\SuperAdminAuthController;
use App\Http\Controllers\AdminController;

Route::post('trebel/register', [AuthController::class, 'register']);

Route::prefix('{user_id}/trebel')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::patch('profile', [AuthController::class, 'updateProfile']);
        Route::delete('delete', [AuthController::class, 'deleteAccount']);
    });
});

Route::post('logout', [AuthController::class, 'logout']);

// ✅ Refresh token endpoint
Route::post('token/refresh', [AuthController::class, 'refreshToken']);

Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'sendOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
    Route::post('resend', [OtpController::class, 'resendOtp']);
});

Route::prefix('superadmin')->group(function () {
    Route::post('register', [SuperAdminAuthController::class, 'register']);
    Route::post('login', [SuperAdminAuthController::class, 'login']);

    // 🛡️ gunakan guard `internal`
    Route::middleware('auth:internal_api')->group(function () {
        Route::post('logout', [SuperAdminAuthController::class, 'logout']);
        Route::get('profile', [SuperAdminAuthController::class, 'profile']);
    });
});

Route::prefix('admin')->group(function () {
    // 🔑 Login untuk Admin
    Route::post('login', [AdminController::class, 'login']);

    // 🛡️ Proteksi dengan guard admin_api
    Route::middleware('auth:admin_api')->group(function () {
        Route::get('profile', [AdminController::class, 'profile']);
        Route::post('logout', [AdminController::class, 'logout']);
    });

    // ✅ Optional: Hanya SuperAdmin yang bisa buat Admin via API
    Route::middleware('auth:internal_api')->group(function () {
        Route::post('/', [AdminController::class, 'store']); // SuperAdmin membuat Admin
    });
});



Route::middleware('auth:api')->prefix('ai')->group(function () {
    Route::post('recommend', [AiController::class, 'recommend']);
    Route::get('mood-tracking', [AiController::class, 'moodTracking']);
    Route::get('greeting', [AiController::class, 'greeting']);
});
