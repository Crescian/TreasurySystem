<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use App\Models\Daily;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Throwable;



class MonthlySummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {
        // Month filter (default: Feb 2026)
        $monthFilter = Carbon::parse(
            $request->input('month', '2026-02-01')
        )->startOfMonth();

        // Base Eloquent query (CTE equivalent)
        $baseQuery = Daily::query()
            ->join('companies as c', 'c.id', '=', 'dailies.company')
            ->join('inflow_type as it', 'it.id', '=', 'dailies.inflow_type')
            ->selectRaw("
            dailies.customer,
            c.company AS company_name,
            c.type AS company_type,
            it.inflow_name AS inflow_type_name,
            dailies.inflow_name AS daily_inflow_name,
            dailies.date AS daily_date,
            date_trunc('month', dailies.date)::date AS month_start,

            ROW_NUMBER() OVER (
                PARTITION BY date_trunc('month', dailies.date)
                ORDER BY dailies.date
            ) AS workday_index,

            REGEXP_REPLACE(dailies.amount, '[^0-9.-]', '', 'g')::numeric AS amount_num
        ")
            ->whereBetween('dailies.date', [
                $monthFilter,
                $monthFilter->copy()->addMonth()
            ])
            ->whereRaw("EXTRACT(ISODOW FROM dailies.date) BETWEEN 1 AND 5")
            ->where('c.type', 'internal'); // ✅ FILTER INTERNAL ONLY

        // Outer query (final SELECT)
        $results = DB::query()
            ->fromSub($baseQuery, 'working_days')
            ->selectRaw("
            customer,
            company_name,
            company_type,
            inflow_type_name,
            daily_inflow_name,
            daily_date,
            month_start,
            CEILING(workday_index / 5.0)::int AS week_no,
            amount_num
        ")
            ->orderBy('daily_date')
            ->orderBy('company_name')
            ->orderBy('customer')
            ->get();

        return response()->json($results);
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
