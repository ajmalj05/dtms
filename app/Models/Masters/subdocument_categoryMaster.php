<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subdocument_categoryMaster extends Model
{
    use HasFactory;

    public $table = "subdocument_category";
    protected $primaryKey = 'id';
    protected $fillable = ['document_category_id,subcategory_name, active_status,is_deleted,created_at,updated_at'];
}
