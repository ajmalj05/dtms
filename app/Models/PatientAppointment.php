<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAppointment extends Model
{
    use HasFactory;
    public $table = "patient_appointment";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'salutation_id', 'patientname','last_name','dob','age',
    'mobile_number','email','gender','department_id','specialist_id','appointment_date',
    'appointment_type','appointment_time','created_by','branch_id','created_at','updated_at','is_cancellled'];

}

