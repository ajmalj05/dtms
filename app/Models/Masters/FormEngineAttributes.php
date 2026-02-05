<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEngineAttributes extends Model
{
    use HasFactory;
    public $table = "formengine_attributes";
    protected $primaryKey = 'id';
    protected $fillable = ['question_id,type_id,attr_value,attr_name'];
}
