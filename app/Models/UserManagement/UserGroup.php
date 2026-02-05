<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserGroup extends Model
{
    use HasFactory;

    public $table = "user_group";
    protected $primaryKey = 'group_id';
  //  protected $fillable = ['group_name,branch_id,created_by,is_deleted'];

  protected $fillable = ['group_name','branch_id','created_by','is_deleted'];

}



