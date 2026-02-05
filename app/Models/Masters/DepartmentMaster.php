<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DepartmentMaster extends Model
{
    use HasFactory;

    public $table = "departments";
    protected $primaryKey = 'id';
    protected $fillable = ['department_name,display_status,created_by,is_deleted'];

}
