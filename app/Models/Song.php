<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'title',
        'album',
        'duration',
        'artist_id',
        'genre_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->song_id = (string) Str::uuid(); // ✅ penting banget!
        });
    }

public function artist()
{
    return $this->belongsTo(Artist::class, 'artist_id', 'artist_id'); // ✅ BENAR, UUID ke UUID
}


    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }
}
