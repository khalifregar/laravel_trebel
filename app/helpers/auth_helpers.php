<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('isSuperadmin')) {
    function isSuperadmin(): bool
    {
        return Auth::guard('internal_web')->check();
    }
}
