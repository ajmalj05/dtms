<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVaccination extends Model
{
    use HasFactory;
    public $table = "patient_vaccination";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'vaccination_date', 'remarks', 'branch_id',
        'display_status', 'created_by', 'is_deleted', 'created_at', 'updated_at','vaccination_id'];

}

