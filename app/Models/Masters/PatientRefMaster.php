<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PatientRefMaster extends Model
{
    use HasFactory;

    public $table = "patient_ref_master";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_ref_name,display_status,created_by,is_deleted'];

}
