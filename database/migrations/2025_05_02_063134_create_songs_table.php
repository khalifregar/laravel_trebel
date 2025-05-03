<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('songs');

        Schema::create('songs', function (Blueprint $table) {
            $table->id(); // internal PK
            $table->uuid('song_id')->unique(); // UUID untuk public

            $table->string('title');
            $table->string('album')->nullable();
            $table->string('duration')->nullable();

            // Relasi ke artist_id berbasis UUID
            $table->uuid('artist_id')->nullable();
            $table->foreign('artist_id')->references('artist_id')->on('artists')->onDelete('set null');

            // Relasi ke genre_id berbasis ID biasa
            $table->foreignId('genre_id')->constrained('genres')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
