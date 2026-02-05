<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDocument extends Model
{
    use HasFactory;
    public $table = "patient_documents";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id', 'branch_id','image', 'display_status', 'created_by', 'is_deleted', 'created_at',
        'updated_at', 'category_id','remarks','type',];

}

