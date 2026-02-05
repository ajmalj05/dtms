<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBpStatus extends Model
{
    use HasFactory;
    public $table = "patient_bpstatus";
    protected $primaryKey = 'id';
    protected $fillable = ['visit_id', 'time', 'bps','bpd','pulse','specialist_id',
        'order_no','branch_id','display_status','created_by','is_deleted','created_at', 'updated_at'];

}

