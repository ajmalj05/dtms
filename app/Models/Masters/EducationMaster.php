<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EducationMaster extends Model
{
    use HasFactory;

    public $table = "education_master";
    protected $primaryKey = 'id';
    protected $fillable = ['education_name,display_status,created_by,is_deleted'];

}
