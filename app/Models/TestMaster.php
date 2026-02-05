<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestMaster extends Model
{
    use HasFactory;
    public $table = "test_master";
    protected $primaryKey = 'id';
    protected $fillable = ['TestId', 'TestName', 'TestRate','TestDepartment','show_test_in_dtms','show_test_in_targets','dtms_order_no'];

}

