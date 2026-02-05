<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationMaster extends Model
{
    use HasFactory;

    public $table = "vaccination_master";
    protected $primaryKey = 'id';
    protected $fillable = ['vaccination_name', 'display_status', 'created_by','updated_by', 'is_deleted', 'created_at', 'updated_at'];

}
