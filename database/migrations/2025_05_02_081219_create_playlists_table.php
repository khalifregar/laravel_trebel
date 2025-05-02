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

            // Optional: playlist bisa dibuat oleh artis atau developer/user
            $table->foreignId('artist_id')->nullable()->constrained('artists')->nullOnDelete();

            // Relasi ke genre
            $table->foreignId('genre_id')->constrained('genres')->onDelete('restrict');

            $table->timestamps();

            // Cegah duplikasi judul dalam satu genre
            $table->unique(['title', 'genre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
