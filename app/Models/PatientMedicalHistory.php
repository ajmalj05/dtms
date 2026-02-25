<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicalHistory extends Model
{
    use \App\Models\Traits\ClearsPatientAiCache;

    use HasFactory;
    public $table = "patient_medicalhistory";
   
}

