<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    //
    use HasFactory;
    // explicitly set the correct table name
    //protected $table = 'monthly_summary';
    // protected $table = 'treasury_system.monthly_summary'; // 👈 schema + table
    protected $table = 'monthly_summary';

    protected $fillable = ["section", "amount", "section_id", "company_id"];



    

    
    public static function getAmount($section, $year, $month)
    {
        $record = self::where('section', $section)
            ->where('year', $year)
            ->where('month', $month)
            ->where('company_id', 'CONSOLIDATED')
            ->first();

        return $record ? $record->amount : 0;
    }
}


