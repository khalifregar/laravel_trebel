<?php

namespace App\Requests;

use App\Models\UserOtp;

class OtpVerifyRequest
{
    public function __construct(
        private int $userId,
        private string $otp
    ) {}

    public function validOtp(): ?UserOtp
    {
        return UserOtp::where('user_id', $this->userId)
            ->where('otp', $this->otp)
            ->where('is_verified', false)
            ->where('expired_at', '>', now())
            ->first();
    }
}
