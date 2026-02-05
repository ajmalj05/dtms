<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEngineQuestions extends Model
{
    use HasFactory;
    public $table = "formengine_questions";
    protected $primaryKey = 'id';
    protected $fillable = ['question,type_id'];
}
