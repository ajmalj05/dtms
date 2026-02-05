<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBillingPayment extends Model
{
    use HasFactory;
    public $table = "patient_billing_payments";
    protected $fillable = ['patient_billing_id', 'total_paid', 'patient_id','created_by','created_at','updated_at',
        'payment_mode', 'reference_number','receipt_number','branch_id'];

}
