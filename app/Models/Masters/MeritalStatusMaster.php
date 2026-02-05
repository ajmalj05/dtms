<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MeritalStatusMaster extends Model
{
    use HasFactory;

    public $table = "merital_status_master";
    protected $primaryKey = 'id';
    protected $fillable = ['merital_status_name,display_status,created_by,is_deleted'];

}
