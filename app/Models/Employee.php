<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee'; // Specify the table name
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_initial',
        'position',
        'department',
        'company',
        'business_unit',
        'created_at',
        'updated_at'
    ];
}
