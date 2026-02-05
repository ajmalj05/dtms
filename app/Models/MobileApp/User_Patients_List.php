<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User_Patients_List extends Model
{
    use HasFactory;
    public $table = "app_patients_user_mapping";
    protected $primaryKey = 'id';
    protected $fillable = ['app_userid','patient_id','created_at','updated_at'];

}
