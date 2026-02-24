<?php

namespace App\Http\Controllers;

use App\Models\Adb;
use App\Models\Company;
use App\Models\Bank;
use App\Models\BankAccounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdbController extends Controller
{
    public function fetchAdbData()
    {
        $companies = Company::all();
        $banks = Bank::all();

        $result = [];

        foreach ($companies as $company) {
            $row = [
                'company' => $company->company,
            ];

            foreach ($banks as $bank) {
                // Fetch the average balance from bank_accounts where:
                // - banks_id = current bank
                // - company_id = current company
                $avgBalance = BankAccounts::where('banks_id', $bank->id)
                    ->where('company_id', $company->id)
                    ->avg('balance');

                // Assign the result
                $row[$bank->bank] = $avgBalance !== null ? number_format($avgBalance, 2) : null;
            }

            $result[] = $row;
        }

        return response()->json([
            'banks' => $banks->map(fn($b) => ['id' => $b->id, 'bank' => $b->bank]),
            'data' => $result,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Adb $adb)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adb $adb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adb $adb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adb $adb)
    {
        //
    }
}
