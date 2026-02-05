<?php

namespace App\Models\MobileApp;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AppUsers extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // ...

    protected $fillable = [
        'name',
        'mobile_number',
        'password',
        'otp',
        'email',
        'address',
        'otpverified',
        'fcm_token'
    ];
    protected $table = 'app_users';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
