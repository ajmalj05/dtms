<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OldTeleMedicineAnswerSheet extends Model
{
    use HasFactory;
    public $table = "old_tele_medicine_answersheets";
    protected $primaryKey = 'id';
    protected $fillable = ['branch_id', 'student_id', 'revisit_code','paper_id','paper_code',
        'questions_id', 'answer_id', 'answer', 'add_date', 'schools_id', 'answerremark', 'telemqmisclastid'];


}
