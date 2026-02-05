<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderMedicineType extends Model
{
    use HasFactory;
    public $table = "app_purchase_medicine_type";
    protected $primaryKey = 'id';
    protected $fillable = ['order_id','medicine_type','image_path','select_id','medicine_name',
    'medicine_status','medicine_amt','medicine_qty','created_by_admin'];

}
