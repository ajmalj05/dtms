<?php
namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Centers extends Model
{
    use HasFactory;
    public $table = "branch_master";
    protected $primaryKey = 'branch_id';
     protected $fillable = [];

}
