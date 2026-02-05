<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLogs extends Model
{
    use HasFactory;
    public $table = "api_logs";
    protected $primaryKey = 'id';
    protected $fillable = ['ip', 'method', 'url','duration','request_body','response','created_at'];
}

