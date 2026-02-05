<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisits extends Model
{
    use HasFactory;
    public $table = "patient_visits";
    protected $fillable = ['visit_type_id', 'specialist_id', 'patient_id','visit_date','notes','branch_id',
        'display_status','created_by','is_deleted','dtms_remarks', 'is_edited', 'old_visit_type_id','ip_admission_id','fibro_scan'];


}
