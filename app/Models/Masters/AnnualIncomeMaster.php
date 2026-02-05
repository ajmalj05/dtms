<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AnnualIncomeMaster extends Model
{
    use HasFactory;
    public $table = "annual_income_master";
    protected $primaryKey = 'id';
    protected $fillable = ['annual_income_name,display_status,created_by,is_deleted'];

}
