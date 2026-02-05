<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PatientSubCategory extends Model
{
    use HasFactory;
    public $table = "patient_sub_category";
    protected $primaryKey = 'id';
    
}
