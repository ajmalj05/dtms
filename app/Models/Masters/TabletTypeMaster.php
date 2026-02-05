<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabletTypeMaster extends Model
{
    use HasFactory;
    public $table = "tablet_type_master";
    protected $primaryKey = 'id';
    protected $fillable = ['tablet_type_name', 'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at','tab'];
}
