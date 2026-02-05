<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IdProodTypeMaster extends Model
{
    use HasFactory;

    public $table = "id_proof_type_master";
    protected $primaryKey = 'id';
    protected $fillable = ['id_proof_name,display_status,created_by,is_deleted'];

}
