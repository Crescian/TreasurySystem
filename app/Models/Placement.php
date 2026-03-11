<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    protected $table = 'placement'; // Specify the table name
    protected $fillable = [
        'company_id', 'bank_account_id', 'placement_date', 'maturity_date',
        'amount', 'interest_rate', 'interest_net', 'no_of_days',
        'interest_income', 'maturity_value', 'status', 'created_at'
    ];
    protected $casts = [
        'placement_date' => 'date',
        'maturity_date' => 'date',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccounts::class, 'bank_account_id');
    }


}
