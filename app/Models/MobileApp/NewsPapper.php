<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NewsPapper extends Model
{
    use HasFactory;
    public $table = "app_newsletters";
    protected $primaryKey = 'id';
    protected $fillable = ['titles','descriptions','news_date','created_at','created_by','updated_by','updated_at','short_description','is_deleted','display_status','news_description','redirect_url','images'];

}
