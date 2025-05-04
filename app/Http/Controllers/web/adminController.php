<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
    {
        return view('superadmin.home.home_admin'); // ✅ Tidak perlu $admins lagi
    }


    public function create()
    {
        return view('superadmin.admins.create_admin');
    }

    public function store(Request $request, AdminService $adminService)
    {
        $validated = $request->validate([
            'email'    => 'required|email|unique:admins,email',
            'username' => 'required|string|unique:admins,username',
            'password' => 'required|min:6',
        ]);

        $admin = $adminService->createAdmin($validated);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Admin created successfully.')
            ->with('created_admin', $admin);
    }
}
