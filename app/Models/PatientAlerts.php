<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAlerts extends Model
{
    use HasFactory;
    public $table = "patient_alert";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'branch_id', 'display_status', 'created_by', 'is_deleted', 'created_at', 'updated_at','psychiatric_medications'];

}

