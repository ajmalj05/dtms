<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestMasterExt extends Model
{
    use HasFactory;
    public $table = "test_master";
    protected $primaryKey = 'id';
    protected $fillable = ['TestId', 'TestName', 'TestRate','TestDepartment','show_test_in_dtms','show_test_in_targets','group_id'
,'result_type','test_code','display_status','is_deleted','unit','report_data','test_method','order_num','dtms_code','chart_order_no','is_smbg'];

}
