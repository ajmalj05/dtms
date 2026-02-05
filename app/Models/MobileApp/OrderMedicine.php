<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderMedicine extends Model
{
    use HasFactory;
    public $table = "app_purchase_medicine";
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','patient_id','order_amt','created_at','order_status','order_remark',
    'updated_at','payment_status','order_master_id','payment_remarks','remarks','invoice_medicine_path'];

}
