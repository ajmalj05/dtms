<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NewsPapperImage extends Model
{
    use HasFactory;
    public $table = "app_newsletters_image";
    protected $primaryKey = 'id';
    protected $fillable = ['image','news_id'];

}
