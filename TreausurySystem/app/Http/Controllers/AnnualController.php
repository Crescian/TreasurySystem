<?php

namespace App\Http\Controllers;

use App\Models\Annual;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Exception;
use App\Models\Daily;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Throwable;

class AnnualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default month/year for the dropdowns
        //$monthName = $request->get('month', Carbon::now()->format('F'));
        $year = $request->get('year', Carbon::now()->year);
        return view('annual', [
            'year' => $year,
        ]);
    }
    /**
     * Return JSON data for the AJAX‐driven table.
     */
    public function data(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $firstTypeRow = DB::table('inflow_type')->first();
        if (!$firstTypeRow) {
            return response()->json([
                'sections' => [],
                'invoiceLabels' => [],
                'months' => [],
                'summary' => [],
            ]);
        }

        $typeColumn = null;
        foreach (get_object_vars($firstTypeRow) as $col => $val) {
            if (in_array($col, ['type', 'inflow_type', 'name', 'category'], true)) {
                $typeColumn = $col;
                break;
            }
        }
        if (!$typeColumn) {
            $allCols = array_keys(get_object_vars($firstTypeRow));
            $typeColumn = $allCols[1];
        }

        $types = DB::table('inflow_type')
            ->select('id', $typeColumn)
            ->orderBy('id')
            ->get();

        $rawSections = [];
        $invoiceLabels = [];
        $allInvoiceIds = [];

        foreach ($types as $typeRow) {
            $parentLabel = $typeRow->$typeColumn;
            $childInvoices = DB::table('inflow')
                ->where('type_id', $typeRow->id)
                ->orderBy('inflow')
                ->select('id', 'inflow')
                ->get();

            $ids = [];
            foreach ($childInvoices as $inv) {
                $invoiceLabels[$inv->id] = $inv->inflow;
                $ids[] = $inv->id;
            }
            if (!empty($ids)) {
                $rawSections[$parentLabel] = $ids;
                $allInvoiceIds = array_merge($allInvoiceIds, $ids);
            }
        }

        $dailies = DB::table('dailies')
            ->join('inflow', 'inflow.inflow', '=', 'dailies.inflow_name')
            ->select(
                DB::raw('EXTRACT(MONTH FROM dailies.date) as month'),
                'dailies.customer',
                'inflow.id as inflow_id',
                'dailies.amount',
                'dailies.date',
                'dailies.inflow_name as category'
            )
            ->whereRaw('EXTRACT(YEAR FROM dailies.date) = ?', [$year])
            ->get();

        $summary = [];
        $sections = [];
        $invoiceHasAny = [];

        foreach ($rawSections as $parentLabel => $invoiceIds) {
            foreach ($invoiceIds as $invId) {
                $invName = $invoiceLabels[$invId];
                $matchingRows = $dailies->where('inflow_id', $invId);
                if ($matchingRows->isEmpty())
                    continue;

                $hasNonzero = false;
                $monthGroups = $matchingRows->groupBy('month');

                foreach ($monthGroups as $month => $monthRows) {
                    $customerGroups = $monthRows->pluck('customer')->unique();

                    foreach ($customerGroups as $customer) {
                        $sum = $monthRows->where('customer', $customer)->sum('amount');
                        if ($sum > 0)
                            $hasNonzero = true;
                        $summary[$parentLabel][$invName]['Consolidated'][$customer]["$month"] = $sum;
                    }
                }

                if ($hasNonzero) {
                    $invoiceHasAny[$invName] = true;
                    $sections[$parentLabel][] = $invName;
                }
            }
        }

        $months = range(1, 12);

        // ✅ Get previous year's January total balance from monthly_summaries
        $previousYear = $year - 1;
        $previousBeginningBalance = DB::table('annuals')
            ->where('period_year', $previousYear)
            ->where('month_period', 1) // ✅ corrected column name
            ->sum('amount');

        Log::info('Response Data:', [
            'sections' => $sections,
            'invoiceLabels' => $invoiceLabels,
            'months' => $months,
            'summary' => $summary,
            'previous_beginning_balance' => $previousBeginningBalance, // 
        ]);

        return response()->json([
            'sections' => $sections,
            'invoiceLabels' => $invoiceLabels,
            'months' => $months,
            'summary' => $summary,
            'previous_beginning_balance' => $previousBeginningBalance, // 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        \Log::info('➡️ Annual store() called', [
            'POST data' => $request->all()
        ]);

        try {
            $values = $request->input('values');
            $periodYear = $request->input('period_year');
            $periodMonth = $request->input('month_period');

            if (!is_array($values) || empty($values)) {
                \Log::warning('⚠️ No values sent:', [$values]);
                return response()->json(['status' => 'no_values'], 400);
            }

            // Assuming only one value entry should be saved annually
            $totalEndingBalance = $values['total_ending_balance'] ?? null;

            DB::table('annuals')->updateOrInsert(
                [
                    'period_year' => $periodYear,
                    'month_period' => $periodMonth,
                ],
                [
                    'amount' => $totalEndingBalance,
                    'currency' => $entry['currency'] ?? 'PHP',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \Log::error('❌ Annual Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Annual $annual)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Annual $annual)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Annual $annual)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Annual $annual)
    {
        //
    }
}
