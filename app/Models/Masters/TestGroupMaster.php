<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TestGroupMaster extends Model
{
    use HasFactory;

    public $table = "test_group_master";
    protected $primaryKey = 'id';
    protected $fillable = ['groupname,display_status,created_by,is_deleted'];

}
