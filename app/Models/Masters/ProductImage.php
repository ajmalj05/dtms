<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    public $table = "products_images";
    protected $primaryKey = 'id';
    protected $fillable = ['product_id', 'product_image', 'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at'];
}
