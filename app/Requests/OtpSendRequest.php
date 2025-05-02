<?php

namespace App\Requests;

use App\Models\UserOtp;
use App\Helpers\OtpFormatter;

class OtpSendRequest
{
    public static function make(UserOtp $otp): array
    {
        $instanceId = env('ULTRAMSG_INSTANCE_ID');
        $token = env('ULTRAMSG_TOKEN');
        $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

        $data = [
            'token' => $token,
            'to'    => $otp->phone,
            'body'  => OtpFormatter::formatMessage($otp->otp, 5),
        ];

        return compact('url', 'data');
    }
}
