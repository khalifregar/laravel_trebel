<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ArtistController;

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
| AI (NUNO) Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->prefix('ai')->group(function () {
    Route::post('recommend', [AiController::class, 'recommend']);
    Route::get('mood-tracking', [AiController::class, 'moodTracking']);
    Route::get('greeting', [AiController::class, 'greeting']);
});

/*
|--------------------------------------------------------------------------
| Playlist Routes
|--------------------------------------------------------------------------
*/
Route::prefix('playlists')->middleware('auth:api')->group(function () {
    Route::get('/', [PlaylistController::class, 'index']); // ✅ list semua playlist
    Route::post('/', [PlaylistController::class, 'store']); // ✅ buat playlist
    Route::get('/{playlist_id}', [PlaylistController::class, 'show']); // ✅ detail
    Route::patch('/{playlist_id}', [PlaylistController::class, 'update']); // ✅ update
    Route::delete('/{playlist_id}', [PlaylistController::class, 'destroy']); // ✅ hapus
});


Route::middleware('auth:api')->group(function () {
    Route::get('/songs', [SongController::class, 'index']); // ✅ list semua lagu
    Route::post('/genres/{slug}/songs', [SongController::class, 'store']); // ✅ tambah lagu berdasarkan slug genre
    Route::patch('/songs/{song_id}', [SongController::class, 'update']); // ✅ update lagu by UUID
    Route::delete('/songs/{song_id}', [SongController::class, 'destroy']); // ✅ hapus lagu by UUID
});

Route::middleware('auth:api')->group(function () {
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::post('/artists', [ArtistController::class, 'store']);
    Route::get('/artists/{artist_id}', [ArtistController::class, 'show']);
    Route::patch('/artists/{artist_id}', [ArtistController::class, 'update']);
    Route::delete('/artists/{artist_id}', [ArtistController::class, 'destroy']);

    // ✅ Tambahan route untuk bikin playlist otomatis ke artis
    Route::post('/artists/{artist_id}/playlists', [ArtistController::class, 'storePlaylist']);
});




Route::middleware('auth:api')->group(function () {
    Route::get('/genres', [GenreController::class, 'index']);     // ✅ list genre
    Route::post('/genres', [GenreController::class, 'store']);    // ✅ tambah genre
    Route::put('/genres/{genre_id}', [GenreController::class, 'update']);   // ✅ update genre
    Route::patch('/genres/{genre_id}', [GenreController::class, 'update']); // ✅ update genre
    Route::delete('/genres/{genre_id}', [GenreController::class, 'destroy']); // ✅ hapus genre
});


