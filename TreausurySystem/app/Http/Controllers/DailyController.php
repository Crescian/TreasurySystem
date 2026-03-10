<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DailyController extends Controller
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

        // Create Daily record
        $Daily = Daily::create([
            'banks_id' => $request->banks_id,
            'bank_accounts_id' => $request->bank_accounts_id,
            'customer' => $request->customer,
            'invoice' => $request->invoice,
            'amount' => $request->amount,
            'company' => $request->company,
            'date' => $request->date,
            'description' => $request->description,
            'flow_type' => $request->flow_type,
            'inflow_type' => $request->inflow_type,
            'inflow_name' => $request->inflow_name
        ]);

        return response()->json([
            'message' => 'Daily created successfully!',
            'Daily' => $Daily
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Daily $daily)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Daily $daily)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Daily $daily)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Daily $daily)
    {
        //
    }
}
