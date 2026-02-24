<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;
    protected $fillable = [
        'banks_id',
        'bank_accounts_id',
        'customer',
        'invoice',
        'amount',
        'company',
        'date',
        'description',
        'flow_type',
        'cust/vend/emp',
        'performed_by',
        'inflow_type',
        'inflow_name'
    ];
}
