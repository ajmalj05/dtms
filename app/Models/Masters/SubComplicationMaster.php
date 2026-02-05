<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubComplicationMaster extends Model
{
    use HasFactory;

    public $table = "subcomplication_master";
    protected $primaryKey = 'id';
    protected $fillable = ['complication_id, subcomplication_name, display_status, created_by, is_deleted'];
}
