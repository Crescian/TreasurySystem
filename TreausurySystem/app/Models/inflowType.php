<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class inflowType extends Model
{
    use Notifiable;
    protected $table = 'inflow_type'; // Specify the table name
    protected $fillable = [
        'id',
        'inflow_name',
        'category',
        'created_at',
        'updated_at'
    ];
}
