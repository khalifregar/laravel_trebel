<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [

        // User (web login)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // User (API JWT)
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        // SuperAdmin (Web form login)
        'internal_web' => [
            'driver' => 'session',
            'provider' => 'super_admin',
        ],

        // SuperAdmin (API JWT via Postman)
        'internal_api' => [
            'driver' => 'jwt',
            'provider' => 'super_admin',
        ],
    ],

    'providers' => [

        // Model user biasa
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Model superadmin
        'super_admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class,
        ],
    ],

    'passwords' => [

        // Reset password untuk user
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
