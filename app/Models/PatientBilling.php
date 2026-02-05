<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBilling extends Model
{
    use HasFactory;
    public $table = "patient_billing";
    protected $primaryKey = 'id';
    protected $fillable = ['PatientLabNo', 'patientName', 'phone','email','Age','Gender','Address','DoctorName','PatientId','visit_id','specialist_id','ipd_id','billing_type','custom_lab_no','is_cancelled','is_result_enterd','bill_remarks','branch_id'];

}
