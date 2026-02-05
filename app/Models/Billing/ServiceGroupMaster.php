<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGroupMaster extends Model
{
    use HasFactory;
    public $table = "service_group_master";
    protected $primaryKey = 'id';
    protected $fillable = ['group_name',  'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at','is_lab_group','department_id'];
}
