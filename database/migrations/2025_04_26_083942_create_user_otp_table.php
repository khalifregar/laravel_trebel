<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // ✅ Tambah user_id dan index
            $table->string('phone')->index();               // Nomor HP user
            $table->string('otp');                          // OTP code (6 digit)
            $table->string('otp_token')->unique();          // Token unik untuk keamanan ekstra
            $table->timestamp('expired_at');                // Waktu expire OTP
            $table->boolean('is_verified')->default(false); // Status OTP (belum/diverifikasi)
            $table->timestamps();                           // created_at & updated_at

            // (Opsional tapi disarankan) Kalau mau relasikan ke tabel users
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
