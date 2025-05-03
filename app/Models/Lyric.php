<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lyric extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'file_path',
        'version',
        'language',
    ];

    /**
     * Relasi ke model Song
     */
    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Optional: Accessor untuk path storage penuh (bisa dipakai di view/controller)
     */
    public function getStorageFullPathAttribute(): string
    {
        return "data/songs/{$this->file_path}";
    }
}
