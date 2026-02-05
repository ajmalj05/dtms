<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OldTeleMedicineQuestion extends Model
{
    use HasFactory;
    public $table = "old_tele_medicine_questions";
    protected $primaryKey = 'id';
    protected $fillable = ['branch_id', 'question', 'paper_nameid','paper_type','add_date',
        'schools_id'];


}
