<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientReminders extends Model
{
    use HasFactory;
    public $table = "patient_reminders";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'date','details','remarks','branch_id', 'display_status', 'created_by', 'is_deleted', 'created_at', 'updated_at'];

}

