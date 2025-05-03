<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminService
{
    /**
     * Membuat admin baru.
     *
     * @param array $data
     * @return Admin
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createAdmin(array $data): Admin
    {
        // Validasi manual (opsional kalau tidak pakai form request)
        $this->validateData($data);

        return Admin::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Validasi input admin (jika tidak pakai FormRequest).
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    protected function validateData(array $data): void
    {
        $validator = validator($data, [
            'email' => 'required|email|unique:admins,email',
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Mendapatkan semua admin.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAdmins()
    {
        return Admin::all();
    }

    /**
     * Menghapus admin.
     *
     * @param int $adminId
     * @return bool|null
     */
    public function deleteAdmin(int $adminId): ?bool
    {
        return Admin::findOrFail($adminId)->delete();
    }
}
