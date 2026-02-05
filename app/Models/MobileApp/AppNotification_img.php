<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AppNotification_img extends Model
{
    use HasFactory;
    public $table = "app_notification_img";
    protected $primaryKey = 'id';
    protected $fillable = ['id','notification_img','notification_id','is_deleted'];

}
