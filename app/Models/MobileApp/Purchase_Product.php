<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Purchase_Product extends Model
{
    use HasFactory;
    public $table = "app_purchase_product";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'patient_id',
        'total_amount',
        'payment_status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'delivery_status',
        'delivery_date',
        'product_id',
        'remarks',
        'status_date',
        'order_status',
        'order_master_id',
        'invoice_product_path'
    ];
}
