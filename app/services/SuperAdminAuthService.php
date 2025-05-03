<?php

namespace App\Services;

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class SuperAdminAuthService
{
    public function register(array $data): array
    {
        if (SuperAdmin::count() > 0) {
            throw new \Exception('Superadmin already exists.');
        }

        $admin = SuperAdmin::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($admin);

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function login(string $login, string $password): array
    {
        $admin = SuperAdmin::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            throw new \Exception('Invalid credentials.');
        }

        $token = JWTAuth::fromUser($admin);

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function profile(): SuperAdmin
    {
        return Auth::guard('internal')->user();
    }
}
