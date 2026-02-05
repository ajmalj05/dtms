<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAnswerSheet extends Model
{
    use HasFactory;
    public $table = "patient_answer_sheet";
    protected $fillable = ['question_type_id', 'patient_id'];
}

