<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxMaster extends Model
{
    use HasFactory;
    public $table = "tax_master";
    protected $primaryKey = 'id';
    protected $fillable = ['tax', 'display_status','is_deleted', 'created_by', 'created_at', 'updated_at'];
}
