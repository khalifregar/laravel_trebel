<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['genre_id', 'name', 'slug'];

    // ✅ Auto generate UUID genre_id saat membuat genre baru
    protected static function booted()
    {
        static::creating(function ($genre) {
            if (!$genre->genre_id) {
                $genre->genre_id = Str::uuid();
            }
        });
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
}
