<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpDetails extends Model
{
    use HasFactory;
    public $table = "pump_details_table";
    protected $primaryKey = 'id';
    protected $fillable = ['modal_number', 'remarks', 'patient_id', 'branch_id', 'display_status', 
    'created_by', 'is_deleted','created_at','updated_at'];

}

