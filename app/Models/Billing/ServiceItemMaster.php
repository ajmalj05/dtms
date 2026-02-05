<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItemMaster extends Model
{
    use HasFactory;
    public $table = 'service_item_master';
    protected $primaryKey = 'id';
    protected $fillable = ['item_name', 'item_amount', 'item_code', 'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at', 'service_group_id'];
}
