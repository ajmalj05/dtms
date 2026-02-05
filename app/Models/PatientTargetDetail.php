<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTargetDetail extends Model
{
    use HasFactory;
    public $table = "patient_target_details";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'visit_id', 'weight_present','weight_target','action_plan','created_at','updated_at'];

}

