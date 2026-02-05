<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmailExtMaster extends Model
{
    use HasFactory;

    public $table = "extension_master";
    protected $primaryKey = 'id';
    protected $fillable = ['extension,display_status,created_by,is_deleted'];

}
