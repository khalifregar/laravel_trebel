<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt',
        'response',
        'mood',
    ];

    // <<< Tambahkan ini di dalam class AiHistory
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
