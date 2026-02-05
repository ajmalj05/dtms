<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientComplication extends Model
{
    use HasFactory;
    public $table = "patient_complication";
    protected $primaryKey = 'id';
    protected $fillable = ['complication_id', 'complication_year', 'examined_date', 'icd_diagnosis', 'icd_code', 'specialist_id', 'branch_id',
        'display_status', 'created_by', 'updated_by', 'is_deleted', 'created_at', 'updated_at', 'sub_complication_id', 'patient_id'];

}

