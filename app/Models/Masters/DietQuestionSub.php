<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DietQuestionSub extends Model
{
    use HasFactory;
    public $table = "questionnaire_sub_lables";
    protected $fillable = ['question_id', 'label','branch_id','label',
        'display_status','created_by','is_deleted','created_at','updated_at'];

}
