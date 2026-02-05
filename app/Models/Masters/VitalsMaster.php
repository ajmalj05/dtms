<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalsMaster extends Model
{
    use HasFactory;

    public $table = "vitals_master";
    protected $primaryKey = 'id';
    protected $fillable = ['vital_name,class_name, display_status, is_deleted'];

}
