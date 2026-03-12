<?php

namespace App\Http\Controllers;

use App\Models\BankAccounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountsController extends Controller
{
    public function AccountController(Request $request, $id)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'flow_type' => 'required|in:Inflow,Outflow',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Find the bank account by ID
        $bankAccount = BankAccounts::find($id);

        if (!$bankAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank account not found.'
            ], 404);
        }

        $amount = $request->input('amount');
        $flowType = $request->input('flow_type');

        // Get old balance before changing it
        $oldBalance = $bankAccount->balance;

        // Check for sufficient balance on outflow
        if ($flowType == 'Outflow' && $oldBalance < $amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance for outflow.'
            ], 400);
        }

        // Update balance based on flow type
        if ($flowType == 'Inflow') {
            $bankAccount->balance += $amount;
        } else {
            $bankAccount->balance -= $amount;
        }

        // Save updated balance
        $bankAccount->save();

        return response()->json([
            'status' => 'success',
            'message' => $flowType == 'Inflow' ? 'Amount added successfully.' : 'Amount subtracted successfully.',
            'old_balance' => $oldBalance,
            'new_balance' => $bankAccount->balance
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
        $request->validate([
            'banks_id' => 'required|integer',
            'account_name' => 'required|string|unique:bank_accounts,account_name',
            'account_number' => 'required|unique:bank_accounts,account_number',
            'balance' => 'nullable|numeric|between:0,999999999999.99',
            'balance_type' => 'required',
            'company_id' => 'required|integer',
        ]);

        $bank = BankAccounts::create([
            'banks_id' => $request->banks_id,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'balance' => $request->balance,
            'balance_type' => $request->balance_type,
            'company_id' => $request->company_id,
        ]);

        return response()->json([
            'message' => 'Bank added successfully.',
            'id' => $bank->id,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($bank_id)
    {
        $bankAccounts = BankAccounts::where('bank_accounts.banks_id', $bank_id)
            ->join('companies', 'bank_accounts.company_id', '=', 'companies.id')
            ->select(
                'bank_accounts.id',
                'bank_accounts.account_name',
                'bank_accounts.account_number',
                'bank_accounts.balance',
                'bank_accounts.balance_type',
                'companies.company as company_name' // alias for the name
            )
            ->orderBy('bank_accounts.id', 'asc')
            ->get();

        return response()->json($bankAccounts);
    }


    public function showPlacements($bank_id)
    {
        $bankAccounts = BankAccounts::where('banks_id', $bank_id)
            ->where('balance_type', 'Operating Placements')
            ->select('id', 'account_name', 'account_number', 'balance', 'balance_type')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($bankAccounts);
    }

    public function balance($id)
    {
        $bankAccount = BankAccounts::where('id', $id)
            ->select('balance')
            ->orderBy('id', 'asc')
            ->first();

        return response()->json($bankAccount);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccounts $bankAccounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccounts $bankAccounts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the record by ID
        $bankAccounts = BankAccounts::find($id);

        if (!$bankAccounts) {
            return response()->json(['message' => 'Account number not found'], 404);
        }

        // Delete the record
        $bankAccounts->delete();

        return response()->json(['message' => 'Account number deleted successfully']);
    }
}
