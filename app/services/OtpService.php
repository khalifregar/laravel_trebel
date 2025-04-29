<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Helpers\OtpFormatter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class OtpService
{
    public function generateOtp(int $user_id, string $phone): UserOtp
    {
        $otp = random_int(100000, 999999);

        return UserOtp::create([
            'user_id' => $user_id,
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

        $messageBody = OtpFormatter::formatMessage($userOtp->otp, 5);

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

    public function verifyOtp(int $user_id, string $otp)
    {
        $userOtp = UserOtp::where('user_id', $user_id)
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

        $user = User::where('user_id', $user_id)->first();

        if (!$user) {
            return ResponseHelper::error('User not found.', 404);
        }

        $token = JWTAuth::fromUser($user);

        return ResponseHelper::success('OTP verified successfully', [
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'user_id' => $user->user_id,
                'email' => $user->email,
                'username' => $user->username,
            ],
        ]);
    }

    public function resendOtp(int $user_id, string $phone)
    {
        UserOtp::where('user_id', $user_id)
            ->where('is_verified', false)
            ->where('expired_at', '>', now())
            ->delete();

        $newOtp = $this->generateOtp($user_id, $phone);

        return $this->sendOtp($newOtp);
    }
}
