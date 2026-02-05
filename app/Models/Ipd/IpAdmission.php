<?php

namespace App\Models\Ipd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAdmission extends Model
{
    use HasFactory;
    public $table = "ip_admission";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'admission_date','discharge_date', 'policy_number','ward_number','bed_number','department_id',
        'specialist_id','created_by','updated_by','created_at','updated_at','discharge_summary', 'is_discharge','branch_id'];

}

