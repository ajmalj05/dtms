<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResults extends Model
{
    use HasFactory;
    public $table = "test_results";
    protected $primaryKey = 'id';
    protected $fillable = ['TestId', 'Labno', 'TestNrml','ResultValue','unit','PatientId', 'is_outside_lab','visit_id','visit_date','is_manual_entry','bill_id','is_smbg'];
}


