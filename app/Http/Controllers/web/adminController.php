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
        $admins = Admin::all();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admins.create_admin');
    }

    public function store(Request $request, AdminService $adminService)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:admins,email',
            'username' => 'required|string|unique:admins,username',
            'password' => 'required|min:6',
        ]);

        $adminService->createAdmin($validated);

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin created successfully.');
    }
}
