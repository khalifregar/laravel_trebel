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

        // Admin (Web form login - dibuat oleh SuperAdmin)
        'admin_web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // Admin (API JWT)
        'admin_api' => [
            'driver' => 'jwt',
            'provider' => 'admins',
        ],
    ],

    'providers' => [

        // User biasa
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // SuperAdmin
        'super_admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class,
        ],

        // Admin (dibuat oleh SuperAdmin)
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
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

        // Reset password untuk admin
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Reset password untuk super admin
        'super_admin' => [
            'provider' => 'super_admin',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
