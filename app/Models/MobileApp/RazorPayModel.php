<?php

namespace App\Models\MobileApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RazorPayModel extends Model
{
    use HasFactory;
    public $table = "razor_pay_payments";
    protected $primaryKey = 'order_master_id';

    protected $fillable = [
        'razorpay_order_id', // Add other fields that you want to mass-assign here
        'order_number',
        'r_amount_paid',
        'r_amount_due',
        'status',
        'purchase_type',
        'order_amount',
        'signature'
        
    ];

}
