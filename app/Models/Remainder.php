<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Remainder extends Model
{
    use HasFactory;
    public $table = "app_remainder_table";
    protected $primaryKey = 'id';
    protected $fillable = ['remainder_text', 'created_at', 'updated_at','patient_id'];


}
