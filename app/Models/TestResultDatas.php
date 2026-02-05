<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultDatas extends Model
{
    use HasFactory;
    public $table = "test_result_datas";
    protected $primaryKey = 'id';
    protected $fillable = ['result_date','test_date','bill_id','reported_by'];
}


