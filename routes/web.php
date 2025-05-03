<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\SuperAdminWebAuthController;
use App\Http\Controllers\Web\Admin\AdminWebAuthController;
use App\Http\Controllers\Web\AdminController; // ← Tambahkan ini

// Redirect root URL to superadmin login
Route::get('/', fn () => redirect()->route('superadmin.login'));

// Superadmin Auth Routes
Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // Auth
    Route::get('/login', [SuperAdminWebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperAdminWebAuthController::class, 'login'])->name('login.post');

    Route::middleware('auth:internal_web')->group(function () {
        Route::get('/dashboard', fn () => view('superadmin.home.home_admin'))->name('dashboard');
        Route::post('/logout', [SuperAdminWebAuthController::class, 'logout'])->name('logout');

        // ✅ SuperAdmin: kelola Admin
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {

    // Login form (GET)
    Route::get('/login', [AdminWebAuthController::class, 'showLoginForm'])->name('login');

    // Login submit (POST)
    Route::post('/login', [AdminWebAuthController::class, 'login'])->name('login.post');

    // Protected area: dashboard + logout
    Route::middleware('auth:admin_web')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::post('/logout', [AdminWebAuthController::class, 'logout'])->name('logout');
    });
});
