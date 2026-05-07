<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Daily;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnnualController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', Carbon::now()->year);
        return view('annual', ['year' => $year]);
    }

    /**
     * Return JSON data for the summary (annual) table.
     * Same financial flow as MonthlySummaryController but columns = months 1–12.
     */
    public function data(Request $request)
    {
        $year = (int) $request->get('year', Carbon::now()->year);

        $yearStart = Carbon::create($year, 1, 1)->startOfDay()->toDateString();
        $yearEnd   = Carbon::create($year, 12, 31)->endOfDay()->toDateString();

        // Base query — same joins as monthly, but EXTRACT(MONTH) instead of workday_index
        $baseQuery = Daily::query()
            ->join('companies as c', 'c.id', '=', 'dailies.company')
            ->join('inflow_type as it', 'it.id', '=', 'dailies.inflow_type')
            ->selectRaw("
                dailies.customer,
                c.id AS company_id,
                c.company AS company_name,
                c.type AS company_type,
                it.inflow_name AS inflow_type_name,
                dailies.inflow_name AS daily_inflow_name,
                dailies.date AS daily_date,
                EXTRACT(MONTH FROM dailies.date)::int AS month_no,
                REGEXP_REPLACE(dailies.amount, '[^0-9.-]', '', 'g')::numeric AS amount_num
            ")
            ->whereBetween('dailies.date', [$yearStart, $yearEnd])
            ->where('c.type', 'internal');

        $results = DB::query()
            ->fromSub($baseQuery, 'base')
            ->selectRaw("customer, company_id, company_name, company_type,
                         inflow_type_name, daily_inflow_name, daily_date,
                         month_no, amount_num")
            ->orderBy('daily_date')
            ->orderBy('company_name')
            ->orderBy('customer')
            ->get();

        $inflowTotals = DB::query()
            ->fromSub($baseQuery, 'base')
            ->selectRaw("
                company_name,
                inflow_type_name,
                daily_inflow_name,
                month_no,
                SUM(amount_num) as inflow_total
            ")
            ->groupBy('company_name', 'inflow_type_name', 'daily_inflow_name', 'month_no')
            ->orderBy('inflow_type_name')
            ->orderBy('daily_inflow_name')
            ->orderBy('company_name')
            ->get();

        $renameMap = [
            'OTHER EXPENSES (INCOME)' => 'OTHER INFLOW / OUTFLOW',
            'CAPEX'                   => 'SUB-TOTAL',
            'NONTRADE TRANSACTIONS'   => 'NET ADVANCES TO AFFILIATE',
            'STOCKHOLDERS'            => 'NET ADVANCES TO STOCKHOLDERS',
            'FINANCE TRANSACTIONS'    => 'NET FINANCE TRANSACTIONS',
        ];

        $inflowTotals = $inflowTotals->map(function ($row) use ($renameMap) {
            $type = strtoupper(trim($row->inflow_type_name));
            if (isset($renameMap[$type])) {
                $row->inflow_type_name = $renameMap[$type];
            }
            return $row;
        });

        // Group by company_month key — same pattern as monthly (week → month)
        $grouped = $inflowTotals->groupBy(fn($r) => "{$r->company_name}_{$r->month_no}");

        // ── financial flow computations (identical logic to monthly) ──────────
        $beforeOtherCapex = $grouped->map(function ($group) {
            $gross = $direct = $indirect = $overhead = $other = 0;
            foreach ($group as $row) {
                $type   = strtoupper(trim($row->inflow_type_name));
                $amount = (float) $row->inflow_total;
                if ($type === 'GROSS REVENUES')               $gross    = $amount;
                elseif ($type === 'DIRECT COSTS')             $direct   = $amount;
                elseif ($type === 'INDIRECT COSTS')           $indirect = $amount;
                elseif ($type === 'OVERHEAD')                 $overhead = $amount;
                elseif ($type === 'OTHER EXPENSES (INCOME)')  $other    = $amount;
            }
            return (object) [
                'company_name'     => $group[0]->company_name,
                'month_no'         => $group[0]->month_no,
                'inflow_type_name' => 'INFLOW / (OUTFLOW) BEFORE OTHER CAPEX',
                'inflow_total'     => $gross - $direct - $indirect - $overhead - $other,
            ];
        });

        $endingCashBeforeCapex = $grouped->map(function ($group) use ($beforeOtherCapex) {
            $company = $group[0]->company_name;
            $month   = $group[0]->month_no;
            $before  = $beforeOtherCapex->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
            return (object) [
                'company_name'     => $company,
                'month_no'         => $month,
                'inflow_type_name' => 'INFLOW / (OUTFLOW) BEF ADV TO AFF.',
                'inflow_total'     => $before ? $before->inflow_total : 0,
            ];
        });

        $inflowOutflowAff = $grouped->map(function ($group) use ($endingCashBeforeCapex) {
            $company       = $group[0]->company_name;
            $month         = $group[0]->month_no;
            $beforeCapex   = $endingCashBeforeCapex->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
            $capexSubtotal = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'CAPEX') $capexSubtotal = (float) $row->inflow_total;
            }
            return (object) [
                'company_name'     => $company,
                'month_no'         => $month,
                'inflow_type_name' => 'INFLOW / (OUTFLOW) BEF ADV TO AFF.',
                'inflow_total'     => ($beforeCapex ? $beforeCapex->inflow_total : 0) - $capexSubtotal,
            ];
        });

        $inflowOutflowStockholders = $grouped->map(function ($group) use ($inflowOutflowAff) {
            $company   = $group[0]->company_name;
            $month     = $group[0]->month_no;
            $befAdvAff = $inflowOutflowAff->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
            $netAdvAff = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET ADVANCES TO AFFILIATE') $netAdvAff = (float) $row->inflow_total;
            }
            return (object) [
                'company_name'     => $company,
                'month_no'         => $month,
                'inflow_type_name' => 'INFLOW / (OUTFLOW) AFT ADV TO AFF.',
                'inflow_total'     => ($befAdvAff ? $befAdvAff->inflow_total : 0) - $netAdvAff,
            ];
        });

        $inflowOutflowFinanceTransactions = $grouped->map(function ($group) use ($inflowOutflowStockholders) {
            $company     = $group[0]->company_name;
            $month       = $group[0]->month_no;
            $befStock    = $inflowOutflowStockholders->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
            $netAdvStock = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET ADVANCES TO STOCKHOLDERS') $netAdvStock = (float) $row->inflow_total;
            }
            return (object) [
                'company_name'     => $company,
                'month_no'         => $month,
                'inflow_type_name' => 'INFLOW / (OUTFLOW) FINANCE TRANSACTIONS',
                'inflow_total'     => ($befStock ? $befStock->inflow_total : 0) - $netAdvStock,
            ];
        });

        $netCash = $grouped->map(function ($group) use ($inflowOutflowFinanceTransactions) {
            $company       = $group[0]->company_name;
            $month         = $group[0]->month_no;
            $beforeFinance = $inflowOutflowFinanceTransactions->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
            $netFinance    = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET FINANCE TRANSACTIONS') $netFinance = (float) $row->inflow_total;
            }
            return (object) [
                'company_name'     => $company,
                'month_no'         => $month,
                'inflow_type_name' => 'NET CASH INFLOW',
                'inflow_total'     => ($beforeFinance ? $beforeFinance->inflow_total : 0) - $netFinance,
            ];
        });

        // ── per-company beginning balance: previous year December ─────────────
        $prevYear = $year - 1;

        $bbFromDB = DB::table('beginning_balances')
            ->join('companies', 'companies.id', '=', 'beginning_balances.company')
            ->where('beginning_balances.year', $prevYear)
            ->where('beginning_balances.month', 12)
            ->select('companies.company as company_name', 'beginning_balances.amount')
            ->get()
            ->keyBy('company_name');

        $bbLabel = 'BEGINNING BALANCE 12.31.' . substr($prevYear, -2);

        // ── ENDING BALANCE with month-over-month carryover ────────────────────
        // January beginning = DB balance; each subsequent month beginning = prior month ending balance.
        $allCompanies = $grouped->map(fn($g) => $g[0]->company_name)->unique()->sort()->values();
        $allMonths    = $grouped->map(fn($g) => $g[0]->month_no)->unique()->sort()->values();

        // company_name → company_id lookup for saving
        $companyIdMap = $results->pluck('company_id', 'company_name');

        $beginningBalanceRows  = collect();
        $endingBalanceRows     = collect();
        $lastEndingByCompany   = [];   // tracks each company's final ending balance to persist

        foreach ($allCompanies as $company) {
            $prevEndingBalance = isset($bbFromDB[$company]) ? (float) $bbFromDB[$company]->amount : 0;

            foreach ($allMonths as $month) {
                if (!$grouped->has("{$company}_{$month}")) continue;

                $beginningBalance = $prevEndingBalance;
                $netCashRow       = $netCash->first(fn($r) => $r->company_name === $company && $r->month_no === $month);
                $netCashAmount    = $netCashRow ? (float) $netCashRow->inflow_total : 0;
                $endingAmount     = $netCashAmount + $beginningBalance;

                $beginningBalanceRows->push((object) [
                    'company_name'     => $company,
                    'month_no'         => $month,
                    'inflow_type_name' => $bbLabel,
                    'inflow_total'     => $beginningBalance,
                ]);

                $endingBalanceRows->push((object) [
                    'company_name'     => $company,
                    'month_no'         => $month,
                    'inflow_type_name' => 'ENDING BALANCE',
                    'inflow_total'     => $endingAmount,
                ]);

                $prevEndingBalance             = $endingAmount;
                $lastEndingByCompany[$company] = ['amount' => $endingAmount, 'month' => $month];
            }
        }

        // ── Auto-save last month's ending balance as this year's record ──────────
        // Saved as year=current_year, month=last_month so next year's annual load
        // reads it via: prevYear=current_year, month=12.
        // Also feeds monthly for January of next year via its fallback to month=12.
        foreach ($lastEndingByCompany as $company => $data) {
            $companyId = $companyIdMap[$company] ?? null;
            if (!$companyId) continue;

            DB::table('beginning_balances')->updateOrInsert(
                ['company' => $companyId, 'year' => $year, 'month' => $data['month']],
                ['amount' => $data['amount'], 'updated_at' => now()]
            );
        }

        $beginningBalanceRow = $beginningBalanceRows;
        $endingBalance       = $endingBalanceRows;

        return response()->json([
            'details'       => $results,
            'inflow_totals' => $inflowTotals,

            'before_other_capex'              => $beforeOtherCapex,
            'before_affiliate'                => $endingCashBeforeCapex,
            'after_capex'                     => $inflowOutflowAff,
            'after_affiliate_advances'        => $inflowOutflowStockholders,
            'finance_transactions'            => $inflowOutflowFinanceTransactions,
            'net_cash'                        => $netCash,
            'beginning_balance_row'           => $beginningBalanceRow,
            'beginning_balance_label'         => $bbLabel,
            'ending_balance'                  => $endingBalance,
        ]);
    }

    public function store(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}
