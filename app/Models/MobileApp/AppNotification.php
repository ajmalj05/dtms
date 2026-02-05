<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AppNotification extends Model
{
    use HasFactory;
    public $table = "app_notification";
    protected $primaryKey = 'id';
    protected $fillable = ['titles','locations','event_date','expiry_date','display_status','created_by','created_at','updated_by','updated_at','short_description','detailed_description'];

}
