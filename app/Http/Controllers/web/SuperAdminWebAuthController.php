<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminWebAuthController extends Controller
{
    /**
     * Display the login form for Super Admins.
     */
    public function showLoginForm()
    {
        return view('superadmin.auth.login');
    }

    /**
     * Handle login request for Super Admins.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->login,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');

        if (Auth::guard('internal_web')->attempt($credentials, $remember)) {
            $request->session()->regenerate(); // prevent session fixation
            return redirect()->route('superadmin.dashboard'); // <-- ini benar
        }


        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Incorrect username/email or password.']);
    }

    /**
     * Logout the authenticated Super Admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('internal')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }
}
