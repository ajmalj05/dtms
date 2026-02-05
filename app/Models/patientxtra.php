<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient_xtra extends Model
{
    use HasFactory;
    public $table = "patient_xtra";
    protected $primaryKey = 'id';
    protected $fillable = ['visit_id', 'vitals_id', 'vitals_value','branch_id',
        'display_status','created_by','updated_by','is_deleted','created_at','updated_at', 'patient_id'];

}

