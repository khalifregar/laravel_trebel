<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\LyricController;

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

Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'sendOtp']);
    Route::post('verify', [OtpController::class, 'verifyOtp']);
    Route::post('resend', [OtpController::class, 'resendOtp']);
});

Route::middleware('auth:api')->prefix('ai')->group(function () {
    Route::post('recommend', [AiController::class, 'recommend']);
    Route::get('mood-tracking', [AiController::class, 'moodTracking']);
    Route::get('greeting', [AiController::class, 'greeting']);
});

Route::prefix('playlists')->middleware('auth:api')->group(function () {
    Route::get('/', [PlaylistController::class, 'index']);
    Route::post('/', [PlaylistController::class, 'store']);
    Route::get('/{playlist_id}', [PlaylistController::class, 'show']);
    Route::patch('/{playlist_id}', [PlaylistController::class, 'update']);
    Route::delete('/{playlist_id}', [PlaylistController::class, 'destroy']);
});


Route::middleware('auth:api')->group(function () {
    Route::get('/songs', [SongController::class, 'index']);
    Route::post('/genres/{slug}/songs', [SongController::class, 'store']);
    Route::patch('/songs/{song_id}', [SongController::class, 'update']);
    Route::delete('/songs/{song_id}', [SongController::class, 'destroy']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::post('/artists', [ArtistController::class, 'store']);
    Route::get('/artists/{artist_id}', [ArtistController::class, 'show']);
    Route::patch('/artists/{artist_id}', [ArtistController::class, 'update']);
    Route::delete('/artists/{artist_id}', [ArtistController::class, 'destroy']);

    Route::post('/artists/{artist_id}/playlists', [ArtistController::class, 'storePlaylist']);
});

Route::middleware('auth:api')->group(function () {
    // Ambil lirik berdasarkan UUID lagu
    Route::get('/songs/{song_id}/lyrics', [LyricController::class, 'show']);

    // Simpan lirik baru untuk lagu tertentu (admin input)
    Route::post('/songs/{song_id}/lyrics', [LyricController::class, 'store']);
});



Route::middleware('auth:api')->group(function () {
    Route::get('/genres', [GenreController::class, 'index']);
    Route::post('/genres', [GenreController::class, 'store']);
    Route::put('/genres/{genre_id}', [GenreController::class, 'update']);
    Route::patch('/genres/{genre_id}', [GenreController::class, 'update']);
    Route::delete('/genres/{genre_id}', [GenreController::class, 'destroy']);
});


