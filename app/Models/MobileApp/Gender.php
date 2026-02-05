<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;
    public $table = "app_gender";
    protected $primaryKey = 'id';
    protected $fillable = ['gender','code'];

}
