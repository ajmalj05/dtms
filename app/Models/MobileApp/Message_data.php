<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Message_data extends Model
{
    use HasFactory;
    public $table = "app_message_data";
    protected $primaryKey = 'id';
    protected $fillable = ['patient_id','user_id','message_text','admin_id','created_at','read_status'];

}
