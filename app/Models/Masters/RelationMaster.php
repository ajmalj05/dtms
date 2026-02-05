<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RelationMaster extends Model
{
    use HasFactory;

    public $table = "relation_master";
    protected $primaryKey = 'id';
    protected $fillable = ['relation_name,display_status,created_by,is_deleted'];

}
