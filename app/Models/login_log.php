<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'login_time',
        'login_ip',
    ];
    public $table = "login_log";
}

