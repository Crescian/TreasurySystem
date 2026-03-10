<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Logs extends Model
{
    use Notifiable;
    protected $table = 'logs'; // Specify the table name
    protected $fillable = [
        'date',
        'account_name',
        'amount',
        'invoice',
        'description',
        'flow_type',
        'created_at',
        'report_type',
        'cust/vend/emp',
        'performed_by',
        'inflow_type',
        'inflow_name'
    ];
}
