<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\DietQuestionSub;
class DietQuestionMaster extends Model
{
    use HasFactory;
    public $table = "questionnaire_master";
    protected $primaryKey = 'id';
    protected $fillable = ['question', 'order_no', 
        'display_status','created_by','is_deleted','dtms_remarks','type'];

        public function sub_question()
        {
           return $this->hasMany(DietQuestionSub::class,'question_id');
        }
}
