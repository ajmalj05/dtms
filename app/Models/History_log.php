<?php

// namespace App\Models\Public;
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'primarykeyvalue_Id',
        'user_id',
        'log_type',
        'table_name',
        'qury_log',
        'description',
        'created_date',
        'patient_id',
    ];
    public $table = "histroy_log";
}
