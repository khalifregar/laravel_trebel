<?php

namespace App\Helpers;

class OtpFormatter
{
    public static function formatMessage(string $otp, int $expiredMinutes = 5): string
    {
        return "{$otp} adalah kode verifikasi Anda\n\nKode ini kedaluwarsa dalam {$expiredMinutes} menit.";
    }
}
