<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }
    public function daily()
    {
        return view('daily');
    }
    public function adb()
    {
        return view('adb');
    }
    public function monthly_summary()
    {
        return view('monthlySummary');
    }
    public function annual()
    {
        return view('annual');
    }
    public function cashpo()
    {
        return view('cashpo');
    }

    public function company()
    {
        return view('company');
    }

    public function company_vendor()
    {
        return view('companyVendor');
    }

    public function company_customer()
    {
        return view('companyCustomer');
    }

    public function bank()
    {
        return view('bank');
    }
    public function logs()
    {
        return view('logs');
    }

    public function employee()
    {
        return view('employee');
    }

    public function adjustment()
    {
        return view('adjustment');
    }
    public function inflow_category()
    {
        return view('inflowCategory');
    }
    public function placement()
    {
        return view('placement');
    }
    public function ai()
    {
        return view('ai');
    }
}
