<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = ['artist_id', 'name', 'title', 'image_url', 'bio'];

    // UUID auto generate saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->artist_id)) {
                $model->artist_id = (string) Str::uuid();
            }
        });
    }


    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'artist_id', 'artist_id'); // UUID
    }


}
