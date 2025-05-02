<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'playlist_id',
        'title',
        'genre_id',
        'artist_id' // ✅ tambahkan agar bisa mass assign UUID artist
    ];

    protected static function booted()
    {
        static::creating(function ($playlist) {
            $playlist->playlist_id = (string) Str::uuid();
        });
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'genre_id'); // pakai UUID
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'artist_id'); // ✅ UUID-to-UUID
    }
}
