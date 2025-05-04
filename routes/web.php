<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\SuperAdminWebAuthController;
use App\Http\Controllers\Web\Admin\AdminWebAuthController;
use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\Log;

// Redirect root URL ke superadmin login
Route::get('/logtest', function () {
    Log::info('[WEB LOG] Ini dari HTTP route, bukan tinker');
    return 'Done logging';
});
Route::get('/', fn() => redirect()->route('superadmin.login'));

// ============================
// ✅ Shared Dashboard (gabungan admin & superadmin)
// ============================
Route::get('/dashboard', function () {
    if (Auth::guard('internal_web')->check()) {
        $role = 'superadmin';
    } elseif (Auth::guard('admin_web')->check()) {
        $role = 'admin';
    } else {
        abort(403);
    }

    return view('superadmin.home.home_admin', compact('role'));

})->name('shared.dashboard')->middleware(['auth:internal_web,admin_web']);


// ============================
// 🔐 Superadmin Routes
// ============================
Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // Login
    Route::get('/login', [SuperAdminWebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperAdminWebAuthController::class, 'login'])->name('login.post');

    // After login
    Route::middleware('auth:internal_web')->group(function () {
        Route::get('/dashboard', fn () => view('superadmin.home.home_admin'))->name('dashboard'); // optional legacy

        Route::post('/logout', [SuperAdminWebAuthController::class, 'logout'])->name('logout');

        // Kelola Admin
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    });
});


// ============================
// 🔐 Admin Routes
// ============================
Route::prefix('admin')->name('admin.')->group(function () {

    // Login
    Route::get('/login', [AdminWebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminWebAuthController::class, 'login'])->name('login.post');

    // After login
    Route::middleware('auth:admin_web')->group(function () {
        // ⚠️ HAPUS: Route dashboard khusus admin (karena bentrok)
        // Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        Route::post('/logout', [AdminWebAuthController::class, 'logout'])->name('logout');
    });
});
