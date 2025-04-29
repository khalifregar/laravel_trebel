<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OtpService;
use App\Helpers\ResponseHelper;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'phone' => 'required|string|max:20',
        ]);

        $userOtp = $this->otpService->generateOtp(
            $request->user_id,
            $request->phone
        );

        return $this->otpService->sendOtp($userOtp);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'otp' => 'required|string',
        ]);

        return $this->otpService->verifyOtp(
            $request->user_id,
            $request->otp
        );
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'phone' => 'required|string|max:20',
        ]);

        return $this->otpService->resendOtp(
            $request->user_id,
            $request->phone
        );
    }
}
