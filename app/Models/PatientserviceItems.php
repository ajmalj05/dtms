<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientserviceItems extends Model
{

    use HasFactory;
    public $table = "patient_service_items";
    protected $fillable = ['PatientLabNo', 'PatientBillingId', 'serviceItemId','serviceItemAmount','PatientId', 'serviceitem_discount', 'quantity','unit_rate','unit_total','discount_amount'];


}

