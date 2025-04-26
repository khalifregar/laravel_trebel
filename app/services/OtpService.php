<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Helpers\ResponseHelper;
use App\Helpers\OtpFormatter; // ✅ Tambahkan ini
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpService
{
    public function generateOtp(string $phone): UserOtp
    {
        $otp = random_int(100000, 999999);

        return UserOtp::create([
            'phone' => $phone,
            'otp' => $otp,
            'otp_token' => Str::random(16),
            'expired_at' => now()->addMinutes(5),
            'is_verified' => false,
        ]);
    }

    public function sendOtp(UserOtp $userOtp)
    {
        $instanceId = env('ULTRAMSG_INSTANCE_ID');
        $token = env('ULTRAMSG_TOKEN');
        $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

        $messageBody = OtpFormatter::formatMessage($userOtp->otp, 5); // ✅ Pakai helper

        $response = Http::asForm()
            ->withoutVerifying()
            ->post($url, [
                'token' => $token,
                'to' => $userOtp->phone,
                'body' => $messageBody,
            ]);

        if ($response->successful()) {
            return ResponseHelper::success('OTP sent successfully', [
                'otp_token' => $userOtp->otp_token,
                'expired_at' => $userOtp->expired_at->toDateTimeString(),
            ]);
        } else {
            return ResponseHelper::error('Failed to send OTP', 500, [
                'response' => $response->body(),
            ]);
        }
    }

    public function verifyOtp(string $phone, string $otp)
    {
        $userOtp = UserOtp::where('phone', $phone)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$userOtp) {
            return ResponseHelper::error('OTP invalid or expired', 400);
        }

        $userOtp->update([
            'is_verified' => true,
        ]);

        return ResponseHelper::success('OTP verified successfully');
    }

    public function resendOtp(string $phone)
{
    // Hapus OTP lama yang belum diverifikasi dan belum expired
    UserOtp::where('phone', $phone)
        ->where('is_verified', false)
        ->where('expired_at', '>', now())
        ->delete();

    // Generate OTP baru
    $newOtp = $this->generateOtp($phone);

    // Kirim OTP baru
    return $this->sendOtp($newOtp);
}

}
