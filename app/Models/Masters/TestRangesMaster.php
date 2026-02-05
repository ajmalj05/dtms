<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestRangesMaster extends Model
{
    use HasFactory;
    public $table = "test_ranges_master";
    protected $primaryKey = 'id';
    protected $fillable = ['test_range,display_status,created_by,is_deleted'];

}
