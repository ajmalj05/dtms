<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PatientReference extends Model
{
    use HasFactory;

    public $table = "patient_reference_master";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_reference_name,display_status,created_by,is_deleted'];

}
