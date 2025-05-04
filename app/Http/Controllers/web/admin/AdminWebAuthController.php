<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminWebAuthController extends Controller
{
    public function showLoginForm()
    {
        Log::info('[ADMIN LOGIN] Menampilkan halaman login admin');
        return view('superadmin.admins.auth.login'); // atau sesuaikan path view login admin kamu
    }

    public function login(Request $request)
    {
        Log::info('[ADMIN LOGIN] Request login masuk', [
            'login_input' => $request->login,
            'remember' => $request->boolean('remember'),
        ]);

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->login,
            'password' => $request->password,
        ];

        Log::info('[ADMIN LOGIN] Credentials yang digunakan:', [
            'key' => key($credentials),
            'value' => $credentials[key($credentials)],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin_web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::guard('admin_web')->user();

            // ✅ Simpan waktu login terakhir
            $user->last_login_at = now();
            $user->save();

            Log::info('[ADMIN LOGIN] Login berhasil', [
                'user_id' => $user->id ?? null,
                'username' => $user->username ?? null,
                'role' => $user->role ?? null,
                'last_login_at' => $user->last_login_at,
            ]);

            return redirect()->route('shared.dashboard');
        }

        Log::warning('[ADMIN LOGIN] Login gagal', [
            'input' => $request->login,
        ]);

        return back()->withErrors([
            'login' => 'Incorrect username or password.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin_web')->user();
        Log::info('[ADMIN LOGOUT] User logout', [
            'user_id' => $user->id ?? null,
            'username' => $user->username ?? null,
            'role' => $user->role ?? null,
        ]);

        Auth::guard('admin_web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
