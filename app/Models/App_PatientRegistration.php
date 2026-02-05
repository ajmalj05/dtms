<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App_PatientRegistration extends Model
{
    use HasFactory;
    public $table = "app_patientsRegistration";
    protected $primaryKey = 'id';
    protected $fillable = ['use_id','gender_id','first_name','last_name','mobile_number','perm_address','email','relationship_id','whatsapp_number','date_of_birth','salutation','created_by','created_at','updated_at','is_verified','verified_date','verified_by','patient_id','branch_id'];
}
