<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PatientRegistration extends Model
{
    use HasFactory;
    public $table = "patient_registration";
    protected $primaryKey = 'id';
    protected $fillable = ['branch_id','name','prescription_no','dob','age','gender','alternative_number_1_type',
    'alternative_number_1_name','alternative_number_1_number','address','last_name',
    'country_id','state_id','place_id','admission_date','specialist_id',
    'panel','streg','email','email_extension','pincode','sub_division_id',
    'salutation_id','penal_id','religion_id','bar_code','id_proof_type','id_proof_number',
    'created_by','updated_by','mobile_number','whatsapp_number',
    'alternative_number_2_number','alternative_number_2_type','alternative_number_2_name',
    'patient_type','education','occupation','caregiver_name','caregiver_pid','marital_status',
    'department_id','token_number','blood_group_id','patient_reference_type_id',
    'patient_reference_name','annual_income','empanelment_no','claim_id','caregiver_relation','uhidno','admission_date','status','app_otp'
];

}
