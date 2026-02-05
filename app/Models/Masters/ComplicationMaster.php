<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplicationMaster extends Model
{
    use HasFactory;

    public $table = "complication_master";
    protected $primaryKey = 'id';
    protected $fillable = ['complication_name, display_status, created_by, is_deleted, branch_id'];
}
