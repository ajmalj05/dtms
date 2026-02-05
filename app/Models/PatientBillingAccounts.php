<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBillingAccounts extends Model
{
    use HasFactory;
    public $table = "patient_billing_accounts";
    protected $fillable = ['PatientLabNo', 'PatientBillingId', 'TotalAmount','serviceCharge','Discamount','Grossamount',
        'NetAmount','PatientId','patient_billing_mode_id','total_paid','balance_amount', 'discount_in_percentage'];

}
