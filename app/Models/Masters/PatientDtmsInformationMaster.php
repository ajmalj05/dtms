<?php
namespace App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PatientDtmsInformationMaster extends Model
{
    use HasFactory;

    public $table = "patient_dtms_information";
    protected $primaryKey = 'id';
    protected $fillable = ['smoking_question_id','alcohol_question_id','pump_question_id'];

}
