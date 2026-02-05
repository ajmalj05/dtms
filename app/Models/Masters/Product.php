<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table = "products";
    protected $primaryKey = 'id';
    protected $fillable = ['product_name', 'product_description', 'product_rate', 'product_discount_percent', 'branch_id',
        'display_status','is_deleted', 'created_by', 'created_at', 'updated_at', 'tax_id', 'available_quantity'];
}
