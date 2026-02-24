<?php

namespace App\Http\Controllers;

use App\Models\inflow;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class inflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getByType($type_id)
    {
        $inflows = Inflow::where('type_id', $type_id)->get();

        return response()->json($inflows);
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
        $Inflow = Inflow::create([
            'inflow' => $request->inflow,
            'type_id' => $request->type_id
        ]);
        // Return the newly created Inflow's ID
        return response()->json([
            'message' => 'inflow added successfully.',
            'id' => $Inflow->id
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $inflows = Inflow::where('type_id', $id)->get();

        return response()->json($inflows);
    }

    public function show2($id)
    {
        $Inflow = Inflow::findOrFail($id);

        return response()->json($Inflow);
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
