<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank',
        'address',
        'contact'
    ];
    public function bankAccounts()
    {
        return $this->hasMany(\App\Models\BankAccounts::class, 'banks_id');
    }

}
