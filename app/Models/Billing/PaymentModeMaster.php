<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModeMaster extends Model
{
    use HasFactory;
    public $table = "payment_mode_master";
    protected $primaryKey = 'id';
    protected $fillable = ['payment_mode_name',  'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at'];
}
