<?php
namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BloodGlucose extends Model
{
    use HasFactory;
    public $table = "app_patient_blood_glucose";
    protected $primaryKey = 'autoId';
     protected $fillable = [];

}
