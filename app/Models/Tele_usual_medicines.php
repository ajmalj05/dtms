<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Tele_usual_medicines extends Model
{
    use HasFactory;
    public $table = "old_tele_usual_medicines";
    protected $primaryKey = 'id';
    protected $fillable = ['branch_id', 'patient_id', 'tele_medicinerecord_id','revisit_code','tablet_type',
        'tablet_name', 'tab_id', 'qty', 'dose', 'add_date', 'schools_id', 'remark', 'streg', 'name', 'new_datetime'];


}
