<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubDivisionMaster extends Model
{
    use HasFactory;

    public $table = "sub_division";
    protected $primaryKey = 'id';
    protected $fillable = ['sub_division_name, display_status, created_by, is_deleted, updated_at'];


}
