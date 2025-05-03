<?php

namespace App\Services;

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class SuperAdminAuthService
{
    /**
     * Mendaftarkan superadmin (hanya satu).
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
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

    /**
     * Login superadmin (email atau username) via JWT.
     *
     * @param string $login
     * @param string $password
     * @return array
     * @throws \Exception
     */
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

    /**
     * Logout JWT-based session.
     *
     * @return void
     * @throws \Exception
     */
    public function logout(): void
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            throw new \Exception('Token not provided.');
        }

        JWTAuth::invalidate($token);
    }

    /**
     * Get authenticated superadmin profile (web or api).
     *
     * @return SuperAdmin
     */
    public function profile(): SuperAdmin
    {
        return Auth::guard('internal_web')->user()
            ?? Auth::guard('internal_api')->user();
    }
}
