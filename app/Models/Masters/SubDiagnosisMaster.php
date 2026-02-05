<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDiagnosisMaster extends Model
{
    use HasFactory;

    public $table = "subdiagnosis_master";
    protected $primaryKey = 'id';
    protected $fillable = ['diagnosis_id, subdiagnosis_name, display_status, created_by, is_deleted'];
}
