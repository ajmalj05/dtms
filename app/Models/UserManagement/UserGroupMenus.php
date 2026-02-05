<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserGroupMenus extends Model
{
    use HasFactory;

    public $table = "user_group_menus";
    protected $primaryKey = 'id';
    protected $fillable = ['user_group_id,menu_type,menu_id,created_user'];
}

?>
