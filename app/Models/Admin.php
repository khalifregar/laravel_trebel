<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'email',
        'username',
        'password',
        'role', // ⬅️ ini penting
    ];


    protected $hidden = [
        'password',
    ];

    // ✅ JWT support
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // ✅ Login status accessor
    public function getLoginStatusAttribute(): string
    {
        return $this->last_login_at ? 'Active' : 'Belum Login';
    }

    // ✅ Scope search
    public function scopeSearch($query, $term)
    {
        $term = trim($term);

        return $query->where(function ($q) use ($term) {
            $q->where('username', 'like', "%$term%")
              ->orWhere('email', 'like', "%$term%")
              ->orWhere(function ($q2) use ($term) {
                  if (strtolower($term) === 'belum login') {
                      $q2->whereNull('last_login_at');
                  } elseif (strtolower($term) === 'active') {
                      $q2->whereNotNull('last_login_at');
                  }
              });
        });
    }
}
