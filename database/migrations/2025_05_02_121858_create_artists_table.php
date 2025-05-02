<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id(); // internal primary key
            $table->uuid('artist_id')->unique(); // UUID untuk public API

            $table->string('name');
            $table->string('title')->nullable(); // gelar seperti "Indie King", "Rap Queen"
            $table->string('image_url')->nullable();
            $table->text('bio')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
