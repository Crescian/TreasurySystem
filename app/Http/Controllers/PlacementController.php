<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Placement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function groupedByCompany()
    {
        $companies = Company::with(['placements.bankAccount.bank'])->get();

        $grouped = $companies->map(function ($company) {
            $banks = [];

            foreach ($company->placements as $placement) {
                $bankName = $placement->bankAccount->bank->bank ?? 'Unknown Bank';
                $accountName = $placement->bankAccount->account_name ?? 'Unknown Account';

                if (!isset($banks[$bankName])) {
                    $banks[$bankName] = [];
                }

                // Group by account name under each bank
                if (!isset($banks[$bankName][$accountName])) {
                    $banks[$bankName][$accountName] = [
                        'account_name' => $accountName,
                        'placements' => []
                    ];
                }

                $banks[$bankName][$accountName]['placements'][] = [
                    'id' => $placement->id,
                    'company_id' => $company->id,
                    'bank_account_id' => $placement->bankAccount->id ?? null,
                    'placement_date' => $placement->placement_date,
                    'maturity_date' => $placement->maturity_date,
                    'amount' => $placement->amount,
                    'interest_rate' => $placement->interest_rate,
                    'interest_net' => $placement->interest_net,
                    'no_of_days' => $placement->no_of_days,
                    'interest_income' => $placement->interest_income,
                    'maturity_value' => $placement->maturity_value,
                    'status' => $placement->status,
                    'created_at' => $placement->created_at->format('Y-m-d H:i:s'),
                ];

            }

            // Convert associative array to indexed array
            $formattedBanks = [];
            foreach ($banks as $bankName => $accounts) {
                $formattedAccounts = array_values($accounts);
                $formattedBanks[] = [
                    'bank' => $bankName,
                    'bank_accounts' => $formattedAccounts
                ];
            }

            return [
                'id' => $company->id,
                'company' => $company->company,
                'banks' => $formattedBanks
            ];
        });

        return response()->json($grouped);
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
        $data = $request->all();

        // Auto-calculate maturity_value if not provided
        if (empty($data['maturity_value']) && isset($data['amount'], $data['interest_income'])) {
            $data['maturity_value'] = $data['amount'] + $data['interest_income'];
        }

        Placement::create($data);

        return response()->json(['message' => 'Placement added successfully']);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'company_id'        => 'required|integer',
    //         'bank_account_id'   => 'required|integer',
    //         'placement_date'    => 'required|date',
    //         'maturity_date'     => 'required|date|after_or_equal:placement_date',
    //         'amount'            => 'required|numeric',
    //         'interest_rate'     => 'required|numeric',
    //         'interest_net'      => 'required|numeric',
    //         'no_of_days'        => 'required|integer',
    //         'interest_income'   => 'required|numeric',
    //         'maturity_value'    => 'nullable|numeric',
    //     ]);

    //     // Auto-calculate maturity value if not supplied
    //     if (empty($validated['maturity_value'])) {
    //         $validated['maturity_value'] = $validated['amount'] + $validated['interest_income'];
    //     }

    //     Placement::create($validated);

    //     return response()->json(['message' => 'Placement added successfully']);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Placement $placement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Placement $placement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Placement $placement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Placement $placement)
    {
        //
    }
}
