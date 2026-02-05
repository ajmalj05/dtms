<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisMaster extends Model
{
    use HasFactory;

    public $table = "diagnosis_master";
    protected $primaryKey = 'id';
    protected $fillable = ['diagnosis_name, code, display_status, created_by, is_deleted'];
}
