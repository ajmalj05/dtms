<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitTypeMaster extends Model
{
    use HasFactory;

    public $table = "visit_type_master";
    protected $primaryKey = 'id';
    protected $fillable = ['visit_type_name, display_status, created_by, is_deleted'];

}
