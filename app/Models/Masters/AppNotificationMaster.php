<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotificationMaster extends Model
{
    use HasFactory;
    public $table = "app_notification_master";
    protected $primaryKey = 'id';
    protected $fillable = ['message', 'branch_id', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at','updated_by'];
}
