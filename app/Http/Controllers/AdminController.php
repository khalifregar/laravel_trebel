<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminService;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Admin login menggunakan email atau username.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = JWTAuth::fromUser($admin);

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin_api')->factory()->getTTL() * 60,
            'admin' => [
                'email' => $admin->email,
                'username' => $admin->username,
            ],
        ]);
    }

    /**
     * Ambil profil admin yang sedang login.
     */
    public function profile()
    {
        $admin = Auth::guard('admin_api')->user();

        if (!$admin) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return response()->json([
            'admin' => [
                'email' => $admin->email,
                'username' => $admin->username,
            ],
        ]);
    }

    /**
     * Logout admin dan invalidate token JWT.
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Successfully logged out.',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Token invalid or not provided.',
            ], 401);
        }
    }

    /**
     * SuperAdmin membuat admin via API (hanya jika diperlukan).
     */
    public function store(Request $request)
    {
        $superadmin = Auth::guard('internal_api')->user();

        if (!$superadmin) {
            return response()->json([
                'message' => 'Unauthorized. Only SuperAdmin can create admins.',
            ], 403);
        }

        $data = $request->validate([
            'email' => 'required|email|unique:admins,email',
            'username' => 'required|string|unique:admins,username',
            'password' => 'required|min:6',
        ]);

        $admin = $this->adminService->createAdmin($data);

        return response()->json([
            'message' => 'Admin created successfully.',
            'admin' => [
                'email' => $admin->email,
                'username' => $admin->username,
            ],
        ], 201);
    }
}
