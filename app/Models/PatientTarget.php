<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTarget extends Model
{
    use HasFactory;
    public $table = "patient_targets";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'visit_id', 'test_id','target_value','present_value','weight','action_plan','fibro_scan','created_at','updated_at'];

}

