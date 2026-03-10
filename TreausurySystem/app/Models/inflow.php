<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class inflow extends Model
{
    use Notifiable;
    protected $table = 'inflow'; // Specify the table name
    protected $fillable = [
        'type_id',
        'inflow',
        'created_at',
        'updated_at'
    ];
}
