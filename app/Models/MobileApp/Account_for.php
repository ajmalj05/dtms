<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Account_for extends Model
{
    use HasFactory;
    public $table = "app_account_created";
    protected $primaryKey = 'id';
    protected $fillable = ['account_for'];

}
