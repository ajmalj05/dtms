<?php
namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BpStatus extends Model
{
    use HasFactory;
    public $table = "app_patient_bpstatus";
    protected $primaryKey = 'autoId';
     protected $fillable = [];
     protected $casts = [
        'patientId' => 'integer',
        'id'=>'integer',
        'is_verified'=>'integer',

        // 'your_string_column' => 'string', // Add this line for string casting
        // Add more columns if needed
    ];

}
