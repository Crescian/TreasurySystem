<?php

use App\Http\Controllers\AdbController;
use App\Http\Controllers\AnnualController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\inflowController;
use App\Http\Controllers\inflowTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DailyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonthlySummaryController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Page
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/daily', [PageController::class, 'daily'])->name('daily');
    Route::get('/adb', [PageController::class, 'adb'])->name('adb');
    Route::get('/annual', [PageController::class, 'annual'])->name('annual');
    Route::get('/cashpo', [PageController::class, 'cashpo'])->name('cashpo');
    Route::get('/company', [PageController::class, 'company'])->name('company');
    Route::get('/company_vendor', [PageController::class, 'company_vendor'])->name('company_vendor');
    Route::get('/company_customer', [PageController::class, 'company_customer'])->name('company_customer');
    Route::get('/bank', [PageController::class, 'bank'])->name('bank');
    Route::get('/logs', [PageController::class, 'logs'])->name('logs');
    Route::get('/employee', [PageController::class, 'employee'])->name('employee');
    Route::get('/adjustment', [PageController::class, 'adjustment'])->name('adjustment');
    Route::get('/inflow_category', [PageController::class, 'inflow_category'])->name('inflow_category');
    Route::get('/placement', [PageController::class, 'placement'])->name('placement');
    Route::get('/ai', [PageController::class, 'ai'])->name('ai');
    Route::get('/monthly_summary', [PageController::class, 'monthly_summary'])->name('monthly_summary');

    Route::get('/annual', [AnnualController::class, 'index'])
        ->name('annual');

    // 2) The JSON data endpoint:
    Route::get('/annual/data', [AnnualController::class, 'data'])
        ->name('annual.data');

    Route::post('/annual/store', [AnnualController::class, 'store']);
    // Company
    Route::post('/companyAdd', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/companyShow', [CompanyController::class, 'show'])->name('company.show');
    Route::get('/company/{id}', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company/{id}', [CompanyController::class, 'update'])->name('company.update');
    Route::get('/company-balances', [CompanyController::class, 'getCompanyBalances'])->name('company.balances');
    Route::get('/companyShowVendor', [CompanyController::class, 'showVendor'])->name('company.showVendor');
    Route::get('/companyShowCustomer', [CompanyController::class, 'showCustomer'])->name('company.showCustomer');

    // Bank
    Route::post('/bankAdd', [BankController::class, 'store'])->name('bank.store');
    Route::get('/bankShow', [BankController::class, 'show'])->name('bank.show');
    Route::get('/bankShow2/{id}', [BankController::class, 'showSelection'])->name('bank.showSelection');
    Route::get('/bankShow3/{id}', [BankController::class, 'showPlacementSelection'])->name('bank.showPlacementSelection');
    Route::get('/bank/{id}', [BankController::class, 'edit'])->name('bank.edit');
    Route::put('/bank/{id}', [BankController::class, 'update'])->name('bank.update');

    // Bank Accounts
    Route::post('/bankAccountsAdd', [BankAccountsController::class, 'store'])->name('bankAccounts.store');
    Route::get('/bankAccounts/balance/{id}', [BankAccountsController::class, 'balance'])->name('bankAccounts.balance');
    Route::get('/bankAccounts/{id}', [BankAccountsController::class, 'show'])->name('bankAccounts.show');
    Route::get('/bankAccounts2/{id}', [BankAccountsController::class, 'showPlacements'])->name('bankAccounts.showPlacements');
    Route::delete('/bankAccounts/{id}', [BankAccountsController::class, 'destroy'])->name('bankAccounts.destroy');
    Route::post('/bank/{id}', [BankAccountsController::class, 'AccountController'])->name('bank.AccountController');

    // Daily
    Route::post('/daily', [DailyController::class, 'store'])->name('daily.store');

    // Logs
    Route::get('/logsShow', [LogsController::class, 'show'])->name('logs.show');
    Route::post('/logsAdd', [LogsController::class, 'store'])->name('logs.store');

    // Employee
    Route::get('/employees/data', [EmployeeController::class, 'getData'])->name('employees.data');
    Route::post('/employeesAdd', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

    // Inflow Type
    Route::get('/inflowType', [inflowTypeController::class, 'show'])->name('inflowType.show');
    Route::post('/inflowTypeAdd', [inflowTypeController::class, 'store'])->name('inflowType.store');

    // Inflow
    Route::get('/inflows/type/{type_id}', [inflowController::class, 'getByType']);
    Route::get('/inflowss/{id}', [inflowController::class, 'show'])->name('inflows.show');
    Route::get('/inflows/{id}', [inflowController::class, 'show2'])->name('inflows.show2');
    Route::post('/inflowsAdd', [inflowController::class, 'store'])->name('inflows.store');

    // Placement
    Route::get('/api/placements/grouped', [PlacementController::class, 'groupedByCompany']);
    Route::post('/placements/store', [PlacementController::class, 'store'])->name('placements.store');

    // monthly summary
    Route::get('/monthlySummary', [MonthlySummaryController::class, 'index'])->name('monthlySummary.show');

    // ADB
    Route::get('/adb-data', [AdbController::class, 'fetchAdbData']);

    // Dashboard
    Route::get('/api/dashboard/daily', [DashboardController::class, 'daily'])->name('dashboard.daily');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
