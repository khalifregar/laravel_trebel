<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // ✅ TAMBAHKAN INI BRO
    public function render($request, Throwable $exception)
    {
        // Kalau request ke API, balikin JSON
        if ($request->is('api/*')) {
            return response()->json([
                'message' => $exception->getMessage(),
                'error' => true,
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }

        // Selain API (misal web biasa), lanjut normal
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
            'message' => 'Unauthenticated',
            'error' => true,
        ], 401);
    }

    return redirect()->guest('login');
}
}
