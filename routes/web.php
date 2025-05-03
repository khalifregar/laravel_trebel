<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\SuperAdminWebAuthController;

// Redirect root URL to superadmin login
Route::get('/', fn () => redirect()->route('superadmin.login'));

// Superadmin Auth Routes
Route::prefix('superadmin')->group(function () {

    // Show login form (GET)
    Route::get('/login', [SuperAdminWebAuthController::class, 'showLoginForm'])->name('superadmin.login');

    // Handle login submission (POST)
    Route::post('/login', [SuperAdminWebAuthController::class, 'login'])->name('superadmin.login.post');

    // Protected routes with 'internal' guard
    Route::middleware('auth:internal')->group(function () {
        Route::get('/dashboard', fn () => view('superadmin.home.home_admin'))->name('superadmin.dashboard');
        Route::post('/logout', [SuperAdminWebAuthController::class, 'logout'])->name('superadmin.logout');
    });
});
