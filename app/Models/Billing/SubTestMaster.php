<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTestMaster extends Model
{
    use HasFactory;
    public $table = "test_sub_group";
    protected $primaryKey = 'id';
    protected $fillable = ['sub_group_name',  'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at'];
}
