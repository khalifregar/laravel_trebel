<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id(); // internal PK
            $table->uuid('playlist_id')->unique(); // UUID publik untuk API/mobile
            $table->string('title');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('restrict'); // FK ke genres
            $table->timestamps();

            // ✅ Tambahkan constraint kombinasi unik agar tidak ada duplikat nama dalam genre yang sama
            $table->unique(['title', 'genre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
