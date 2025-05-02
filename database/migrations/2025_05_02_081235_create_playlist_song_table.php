<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_song', function (Blueprint $table) {
            $table->id(); // internal PK

            // Relasi ke playlist
            $table->foreignId('playlist_id')
                ->constrained('playlists')
                ->onDelete('cascade');

            // Relasi ke song
            $table->foreignId('song_id')
                ->constrained('songs')
                ->onDelete('cascade');

            $table->timestamps();

            // Tambahkan constraint agar tidak ada lagu yang duplikat di playlist yang sama
            $table->unique(['playlist_id', 'song_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_song');
    }
};
