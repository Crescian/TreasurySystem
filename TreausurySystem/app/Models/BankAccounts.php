<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'banks_id',
        'company_id',
        'account_name',
        'account_number',
        'balance',
        'balance_type'
    ];
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'banks_id');
    }

}
