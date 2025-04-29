<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Api\AiController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
*/
Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'sendOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
    Route::post('resend', [OtpController::class, 'resendOtp']);
});

/*
|--------------------------------------------------------------------------
| AI (NUNO) Routes - Protected by Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('ai')->group(function () {
    Route::post('recommend', [AiController::class, 'recommend']);
    Route::get('mood-tracking', [AiController::class, 'moodTracking']);
});
