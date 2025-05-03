<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SuperAdminAuthService;
use App\Helpers\ResponseHelper;

class SuperAdminAuthController extends Controller
{
    protected SuperAdminAuthService $service;

    public function __construct(SuperAdminAuthService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:super_admins,email',
            'username' => 'required|string|unique:super_admins,username',
            'password' => 'required|string|min:6',
        ]);

        try {
            $result = $this->service->register($request->only(['email', 'username', 'password']));

            return ResponseHelper::success('Superadmin registered successfully.', [
                'email' => $result['admin']->email,
                'username' => $result['admin']->username,
                'access_token' => $result['token'],
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->service->login($request->login, $request->password);

            return ResponseHelper::success('Login successful.', [
                'email' => $result['admin']->email,
                'username' => $result['admin']->username,
                'access_token' => $result['token'],
                'token_type' => 'bearer',
                'expires_in' => auth('internal_api')->factory()->getTTL() * 60,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 401);
        }
    }

    public function logout()
    {
        try {
            Auth::guard('internal_api')->logout();

            return ResponseHelper::success('Logout successful');
        } catch (\Exception $e) {
            return ResponseHelper::error('Logout failed.', 500);
        }
    }

    public function profile()
    {
        try {
            $admin = Auth::guard('internal_api')->user();

            if (! $admin) {
                return ResponseHelper::error('Unauthorized.', 401);
            }

            return ResponseHelper::success('Profile fetched.', [
                'email' => $admin->email,
                'username' => $admin->username,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::error('Unauthorized.', 403);
        }
    }
}
