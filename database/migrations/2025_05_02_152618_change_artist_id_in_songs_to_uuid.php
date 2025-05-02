<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id(); // internal PK
            $table->uuid('song_id')->unique(); // UUID untuk public API

            $table->string('title');
            $table->string('album')->nullable();
            $table->string('duration')->nullable();

            // ✅ UUID foreign key ke artists.artist_id
            $table->uuid('artist_id')->nullable();
            $table->foreign('artist_id')->references('artist_id')->on('artists')->nullOnDelete();

            // ✅ genre tetap pakai foreignId karena genre_id-nya integer
            $table->foreignId('genre_id')->constrained('genres')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
