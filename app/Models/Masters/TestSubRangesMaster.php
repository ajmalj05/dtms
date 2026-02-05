<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubRangesMaster extends Model
{
    use HasFactory;

    public $table = "test_subranges_master";
    protected $primaryKey = 'id';
    protected $fillable = ['test_range_id,test_subrange,display_status,created_by,is_deleted'];
}
