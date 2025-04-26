<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success(string $message = 'Success', mixed $data = null, int $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(string $message = 'Error', int $status = 400, mixed $data = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
