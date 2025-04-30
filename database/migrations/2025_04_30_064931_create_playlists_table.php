<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Nama playlist
            $table->string('slug')->unique();     // Buat URL/link
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('mood_tag')->nullable(); // sad, happy, angry, etc
            $table->string('genre')->nullable();   // pop, jazz, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
