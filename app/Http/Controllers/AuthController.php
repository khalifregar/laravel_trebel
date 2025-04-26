<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ResponseHelper;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // Register
    public function register(Request $request, $user_id = null)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20|unique:users',
        ]);

        // 🔥 Kalau user_id tidak dikasih dari URL, generate otomatis
        if (is_null($user_id)) {
            do {
                $user_id = random_int(1000, 9999);
            } while (User::withTrashed()->where('user_id', $user_id)->exists());
        } else {
            // 🔥 Cek apakah user_id ini sudah pernah ada sebelumnya (termasuk yang sudah dihapus)
            $existingUser = User::withTrashed()
                ->where('user_id', $user_id)
                ->first();

            if ($existingUser) {
                if (!$existingUser->trashed()) {
                    // User masih aktif, tidak boleh daftar ulang
                    return ResponseHelper::error('User ID is already registered.', 409);
                }

                // Kalau user sudah dihapus, cek selisih waktunya
                $deletedAt = $existingUser->deleted_at;
                $now = now();
                $diffInMonths = $deletedAt->diffInMonths($now);

                if ($diffInMonths < 3) {
                    $remaining = 3 - $diffInMonths;
                    return ResponseHelper::error("You can register again after {$remaining} more month(s).", 403);
                }

                // Kalau sudah lebih dari 3 bulan, hapus data lama permanen
                $existingUser->forceDelete();
            }
        }

        // 🔥 Setelah aman, lanjut create user baru
        $user = User::create([
            'user_id' => $user_id,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $userOtp = $this->otpService->generateOtp($request->phone);
        $this->otpService->sendOtp($userOtp);

        $token = JWTAuth::fromUser($user);

        return ResponseHelper::success('Register successful. OTP has been sent.', [
            'id' => $user->id,
            'user_id' => $user->user_id,
            'email' => $user->email,
            'username' => $user->username,
            'access_token' => $token,
        ]);
    }



    // Login
    public function login(Request $request, $user_id)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // 🔥 Cari user termasuk yang soft deleted
        $user = User::withTrashed()
            ->where(function ($query) use ($login) {
                $query->where('email', $login)
                      ->orWhere('username', $login);
            })
            ->where('user_id', $user_id)
            ->first();

        if (!$user) {
            return ResponseHelper::error('User not found.', 404);
        }

        // 🔥 Cek apakah user sudah dihapus (soft delete)
        if ($user->trashed()) {
            return ResponseHelper::error('Your account has been deleted. You cannot login.', 403);
        }

        if (!Hash::check($password, $user->password)) {
            return ResponseHelper::error('Invalid credentials.', 401);
        }

        $otpVerified = \App\Models\UserOtp::where('phone', $user->phone)
            ->where('is_verified', true)
            ->latest('updated_at')
            ->first();

        if (!$otpVerified) {
            return ResponseHelper::error('Please verify your OTP first.', 403);
        }

        $token = JWTAuth::fromUser($user);

        return ResponseHelper::success('Login successful.', [
            'id' => $user->id,
            'user_id' => $user->user_id,
            'email' => $user->email,
            'username' => $user->username,
            'access_token' => $token,
        ]);
    }


    // Logout
    public function logout(Request $request)
    {
        try {
            if (!$token = JWTAuth::getToken()) {
                return ResponseHelper::error('Token not provided', 400);
            }

            JWTAuth::invalidate($token);

            return ResponseHelper::success('Logout successful');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return ResponseHelper::error('Token is invalid', 401);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to logout', 500);
        }
    }

    // Profile (GET)
    public function profile($user_id)
    {
        $user = auth()->user();

        if (!$user || $user->user_id != $user_id) {
            return ResponseHelper::error('Unauthorized access to user profile.', 403);
        }

        return ResponseHelper::success('Profile fetched successfully.', [
            'id' => $user->id,
            'user_id' => $user->user_id,
            'email' => $user->email,
            'username' => $user->username,
            'phone' => $user->phone,
        ]);
    }

    // Update Profile (PATCH)
    public function updateProfile(Request $request, $user_id)
    {
        $user = auth()->user();

        if (!$user || $user->user_id != $user_id) {
            return ResponseHelper::error('Unauthorized access to update profile.', 403);
        }

        $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'username' => 'sometimes|string|unique:users,username,' . $user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'sometimes|string|min:6',
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return ResponseHelper::success('Profile updated successfully.', [
            'id' => $user->id,
            'user_id' => $user->user_id,
            'email' => $user->email,
            'username' => $user->username,
            'phone' => $user->phone,
        ]);
    }

    public function deleteAccount($user_id)
{
    $user = auth()->user();

    if (!$user || $user->user_id != $user_id) {
        return ResponseHelper::error('Unauthorized access to delete account.', 403);
    }

    $user->delete(); // 🔥 Soft delete user

    return ResponseHelper::success('Account deleted successfully. You can register again after 3 months.');
}

}
