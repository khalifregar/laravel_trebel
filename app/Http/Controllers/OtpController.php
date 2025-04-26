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
            'phone' => 'required|string|max:20',
        ]);

        $userOtp = $this->otpService->generateOtp($request->phone);

        return $this->otpService->sendOtp($userOtp);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string',
        ]);

        return $this->otpService->verifyOtp($request->phone, $request->otp);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        return $this->otpService->resendOtp($request->phone);
    }
}
