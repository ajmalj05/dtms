<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SalutationMaster extends Model
{
    use HasFactory;

    public $table = "salutation_master";
    protected $primaryKey = 'id';
    protected $fillable = ['salutation_name,display_status,created_by,is_deleted'];

}
