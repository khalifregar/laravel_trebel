<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['song_id', 'title', 'artist', 'album', 'duration', 'genre_id'];

    protected static function booted()
    {
        static::creating(function ($song) {
            $song->song_id = Str::uuid();
        });
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withTimestamps();
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
