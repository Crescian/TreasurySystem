<?php

namespace App\Http\Controllers;

use App\Models\inflowType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class inflowTypeController extends Controller
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
        $inflowType = inflowType::create([
            'inflow_name' => $request->inflow_name,
            'category' => $request->category
        ]);
        // Return the newly created Inflow's ID
        return response()->json([
            'message' => 'inflow name added successfully.',
            'id' => $inflowType->id,
            'inflow_name' => $inflowType->inflow_name,
            'category' => $inflowType->category,
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function show()
    {
        $inflowType = inflowType::select('id', 'inflow_name', 'category')
            ->orderBy('inflow_name', 'desc')
            ->get();

        // Return the output as a JSON response
        return response()->json($inflowType);

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
