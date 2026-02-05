<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SpecialistMaster extends Model
{
    use HasFactory;

    public $table = "specialist_master";
    protected $primaryKey = 'id';
    protected $fillable = ['specialist_name,specialisation_id,display_status,created_by,is_deleted, email', 'user_id'];

}

