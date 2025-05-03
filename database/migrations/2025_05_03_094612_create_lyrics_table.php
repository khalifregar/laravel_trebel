<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lyrics', function (Blueprint $table) {
            $table->id();

            // ✅ PK integer FK ke songs.id (bukan UUID)
            $table->unsignedBigInteger('song_id');
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');

            $table->string('file_path');       // misal: 'hindia/hindia_evalusia.lrc'
            $table->string('version')->nullable();
            $table->string('language', 10)->default('id');

            $table->timestamps();

            // ✅ Enforce satu lirik per lagu
            $table->unique('song_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lyrics');
    }
};
