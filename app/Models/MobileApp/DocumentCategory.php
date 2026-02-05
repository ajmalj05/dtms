<?php
namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DocumentCategory extends Model
{
    use HasFactory;
    public $table = "document_category";
    protected $primaryKey = 'id';
     protected $fillable = ['category_name','active_status','is_deleted'];

}