<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReligionMaster extends Model
{
    use HasFactory;

    public $table = "religion_master";
    protected $primaryKey = 'id';
    protected $fillable = ['religion_name,display_status,created_by,is_deleted'];

}
