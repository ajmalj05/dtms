<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xtra_fields extends Model
{
    use HasFactory;

    public $table = "xtra_fields";
    protected $primaryKey = 'id';
    protected $fillable = ['test_name, display_status, is_deleted,order, class_name,unit_name'];
}
