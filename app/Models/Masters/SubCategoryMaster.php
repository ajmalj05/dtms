<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubCategoryMaster extends Model
{
    use HasFactory;

    public $table = "sub_category_master";
    protected $primaryKey = 'id';
    protected $fillable = ['sub_category_name,display_status,created_by,is_deleted'];

}
