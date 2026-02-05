<?php

namespace App\Models\Dtms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMiscellaneousDetails extends Model
{
    use HasFactory;

    public $table ='patient_miscellaneous_details';
    protected $fillable = ['patient_id','branch_id','display_status','created_by','is_deleted','height','weight','bmi','scr','gfr','answer_sheet_id'];

}
