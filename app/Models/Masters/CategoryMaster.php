<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategoryMaster extends Model
{
    use HasFactory;

    public $table = "category_master";
    protected $primaryKey = 'id';
    protected $fillable = ['category_name,display_status,created_by,is_deleted'];

}
