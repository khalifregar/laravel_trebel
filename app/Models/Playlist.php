<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['playlist_id', 'title', 'genre_id'];

    protected static function booted()
    {
        static::creating(function ($playlist) {
            $playlist->playlist_id = Str::uuid();
        });
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }
}
