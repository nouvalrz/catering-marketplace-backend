<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- import Auth Laravel
use Tymon\JWTAuth\Contracts\JWTSubject;                 // <-- import JWTSubject
use Illuminate\Support\Carbon;


class Administrators extends Authenticatable implements JWTSubject
{
    use HasFactory;
    
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'remember_token',
        // 'user_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
