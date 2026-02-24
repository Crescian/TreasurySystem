<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
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
        $logs = Logs::create([
            'date' => $request->date ?? 'N/A',
            'account_name' => $request->account_name ?? 'N/A',
            'amount' => $request->amount ?? 0,
            'invoice' => $request->invoice ?? 5,
            'description' => $request->description ?? 'N/A',
            'flow_type' => $request->flow_type ?? 'N/A',
            'cust/vend/emp' => $request->vendorcustomer ?? 'N/A',
            'performed_by' => $request->performed_by ?? 'N/A',
            'report_type' => $request->report_type ?? 'N/A',
            'inflow_type' => $request->inflow_type ?? 'N/A',
            'inflow_name' => $request->inflow_name ?? 'N/A'
        ]);

        return response()->json([
            'message' => 'Logs added successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */

     public function show()
    {
         $logs = Logs::select(
            'logs.id',
            'logs.date',
            'logs.account_name',
            'logs.invoice',
            'logs.amount',
            'inflow_type.inflow_name',
            'logs.description',
            'logs.flow_type',
            'logs.created_at',
            'logs.performed_by',
            'logs.cust/vend/emp', // handle special column name
            'logs.report_type',
            'logs.inflow_type',
            'bank_accounts.account_name as acc_name',
            'banks.bank'
            )
            ->join('bank_accounts', 'bank_accounts.id', '=', 'logs.account_name')
            ->join('banks', 'banks.id', '=', 'bank_accounts.banks_id')
            ->join('inflow_type', function ($join) {
                // Safely cast logs.invoice to integer if it's numeric only
                $join->on('inflow_type.id', '=', DB::raw('CAST(logs.inflow_type AS INTEGER)'));
            })
            ->orderBy('logs.date', 'asc')
            ->limit(30)
            ->get();

        return response()->json($logs);

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logs $logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logs $logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logs $logs)
    {
        //
    }
}
