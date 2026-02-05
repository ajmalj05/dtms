<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPrescriptions extends Model
{
    use HasFactory;
    public $table = "patient_prescriptions";
    protected $primaryKey = 'id';
    protected $fillable = [ 'branch_id','visit_id','tablet_type_id','medicine_id','dose','remarks',
        'display_status', 'created_by', 'updated_by', 'is_deleted', 'created_at', 'updated_at',  'patient_id'];

}
