<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineMaster extends Model
{
    use HasFactory;

    public $table = "medicine_master";
    protected $primaryKey = 'id';
    protected $fillable = ['tablet_type_id', 'medicine_name', 'route', 'notes', 'dose', 'display_status', 'created_by', 'is_deleted', 'created_at', 'updated_at','generic_name'];
}
