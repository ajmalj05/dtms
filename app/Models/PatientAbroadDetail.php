<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAbroadDetail extends Model
{
    use HasFactory;
    public $table = "patient_abroad_details";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_name','phone_no','email_id','address','patient_id', 'branch_id', 'display_status', 'created_by', 'is_deleted', 'created_at', 'updated_at'];

}

