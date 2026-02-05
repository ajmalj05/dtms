<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BloodGroupMaster extends Model
{
    use HasFactory;

    public $table = "blood_group_master";
    protected $primaryKey = 'id';
    protected $fillable = ['blood_group_name,display_status,created_by,is_deleted'];

}
