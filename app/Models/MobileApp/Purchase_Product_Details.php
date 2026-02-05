<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Purchase_Product_Details extends Model
{
    use HasFactory;
    public $table = "app_purchase_product_details";
    protected $primaryKey = 'id';
    protected $fillable = ['order_id','name','product_id','product_image','unit_total','product_price','product_qty','created_by','updated_by','created_at','updated_at'];

}
