<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OccupationMaster extends Model
{
    use HasFactory;

    public $table = "occupation_master";
    protected $primaryKey = 'id';
    protected $fillable = ['occupation_name,display_status,created_by,is_deleted'];

}
