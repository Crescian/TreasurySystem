<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
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
        $bank = Bank::create([
            'bank' => $request->bank
        ]);
        // Return the newly created Bank's ID
        return response()->json([
            'message' => 'Bank added successfully.',
            'id' => $bank->id // Return the ID
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function show(Bank $bank)
    {
        $banks = Bank::select('id', 'bank', 'address', 'contact')
            ->with([
                'bankAccounts' => function ($query) {
                    $query->select('id', 'banks_id', 'company_id');
                }
            ])
            ->orderBy('bank', 'asc')
            ->get()
            ->map(function ($bank) {
                // Get unique company_ids linked to this bank
                $companyIds = $bank->bankAccounts->pluck('company_id')->unique()->values();
                return [
                    'id' => $bank->id,
                    'bank' => $bank->bank,
                    'address' => $bank->address,
                    'contact' => $bank->contact,
                    'company_ids' => $companyIds, // You can rename this depending on what your frontend needs
                ];
            });

        return response()->json($banks);
    }

    public function showSelection($id)
    {
        $banks = DB::table('bank_accounts')
            ->join('banks', 'bank_accounts.banks_id', '=', 'banks.id')
            ->where('bank_accounts.company_id', $id)
            ->select('banks.id', 'banks.bank', 'banks.address', 'banks.contact')
            ->orderBy('banks.bank', 'asc')
            ->distinct() // optional: in case multiple accounts exist per bank
            ->get();

        return response()->json($banks);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $id)
    {
        //
        return response($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the bank by ID
        $bank = Bank::findOrFail($id);

        // Update bank details
        $bank->address = $request->input('address');
        $bank->contact = $request->input('contact');
        $bank->save(); // Save the updated details

        return response()->json([
            'message' => 'Bank updated successfully',
            'bank' => $bank
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
