<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{
    use HasFactory;
    public $table = "patient_diagnosis";
    protected $primaryKey = 'id';
    protected $fillable = ['diagnosis_id', 'diagnosis_year', 'examined_date','icd_diagnosis','icd_code','specialist_id','branch_id',
        'display_status','created_by','updated_by','is_deleted','created_at','updated_at', 'patient_id','is_question_mark','newly_diagnosed','diagnosis_month','sub_diagnosis_id'];

}

