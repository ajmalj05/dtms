<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockManagement extends Model
{
    use HasFactory;
    public $table = "stock_management";
    protected $primaryKey = 'id';
    protected $fillable = ['product_id', 'stock', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at'];
}
