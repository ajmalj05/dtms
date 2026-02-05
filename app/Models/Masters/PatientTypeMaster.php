<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PatientTypeMaster extends Model
{
    use HasFactory;

    public $table = "patient_type_master";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_type_name,display_status,created_by,is_deleted'];

}
