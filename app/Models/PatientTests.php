<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTests extends Model
{

    use HasFactory;
    public $table = "patient_tests";
    protected $fillable = ['PatientLabNo', 'PatientBillingId', 'TestId','TestName','TestRate'];

    
}

