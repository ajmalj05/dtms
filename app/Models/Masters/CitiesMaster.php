<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CitiesMaster extends Model
{
    use HasFactory;

    public $table = "cities";
    protected $primaryKey = 'id';
    protected $fillable = ['branch_id,name,city_state,state_id,pincode'];



}
