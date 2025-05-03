<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SuperAdmin extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'super_admins';

    protected $fillable = [
        'email',
        'username',
        'password',
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
}
