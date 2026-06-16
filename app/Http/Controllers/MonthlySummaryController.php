<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Daily;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlySummaryController extends Controller
{
    public function index(Request $request)
    {
        $monthFilter = Carbon::parse(
            $request->input('month', now()->startOfMonth()->toDateString())
        )->startOfMonth();

        // Base Eloquent query
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
                date_trunc('month', dailies.date)::date AS month_start,

                ROW_NUMBER() OVER (
                    PARTITION BY date_trunc('month', dailies.date)
                    ORDER BY dailies.date
                ) AS workday_index,

                REGEXP_REPLACE(dailies.amount, '[^0-9.-]', '', 'g')::numeric AS amount_num
            ")
            ->whereBetween('dailies.date', [
                $monthFilter->toDateString(),
                $monthFilter->copy()->endOfMonth()->toDateString(),
            ])
            ->whereRaw("EXTRACT(ISODOW FROM dailies.date) BETWEEN 1 AND 5")
            ->where('c.type', 'internal');

        // Outer query — adds week_no
        $results = DB::query()
            ->fromSub($baseQuery, 'working_days')
            ->selectRaw("
                customer,
                company_id,
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

        $inflowTotals = DB::query()
            ->fromSub($baseQuery, 'working_days')
            ->selectRaw("
                company_name,
                inflow_type_name,
                daily_inflow_name,
                CEILING(workday_index / 5.0)::int AS week_no,
                SUM(amount_num) as inflow_total
            ")
            ->groupBy(
                'company_name',
                'inflow_type_name',
                'daily_inflow_name',
                DB::raw("CEILING(workday_index / 5.0)::int")
            )
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

        $grouped = $inflowTotals->groupBy(function ($row) {
            return $row->company_name . '_' . $row->week_no;
        });

        // ── INFLOW / (OUTFLOW) BEFORE OTHER CAPEX ──────────────────────────────
        $beforeOtherCapex = $grouped->map(function ($group) {
            $gross = $direct = $indirect = $overhead = $other = 0;
            foreach ($group as $row) {
                $type   = strtoupper(trim($row->inflow_type_name));
                $amount = (float) $row->inflow_total;
                if ($type === 'GROSS REVENUES')          $gross    = $amount;
                elseif ($type === 'DIRECT COSTS')        $direct   = $amount;
                elseif ($type === 'INDIRECT COSTS')      $indirect = $amount;
                elseif ($type === 'OVERHEAD')            $overhead = $amount;
                elseif ($type === 'OTHER EXPENSES (INCOME)') $other = $amount;
            }
            return (object) [
                'company_name'    => $group[0]->company_name,
                'week_no'         => $group[0]->week_no,
                'inflow_type_name'=> 'INFLOW / (OUTFLOW) BEFORE OTHER CAPEX',
                'daily_inflow_name' => null,
                'inflow_total'    => $gross - $direct - $indirect - $overhead - $other,
            ];
        });

        $beginningBalanceCapex = $grouped->map(function ($group) {
            return (object) [
                'company_name'    => $group[0]->company_name,
                'week_no'         => $group[0]->week_no,
                'inflow_type_name'=> 'BEGINNING BALANCE - CAPEX',
                'daily_inflow_name' => null,
                'inflow_total'    => 0,
            ];
        });

        $endingCashBeforeCapex = $grouped->map(function ($group) use ($beforeOtherCapex, $beginningBalanceCapex) {
            $company = $group[0]->company_name;
            $week    = $group[0]->week_no;
            $before    = $beforeOtherCapex->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $beginning = $beginningBalanceCapex->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'INFLOW / (OUTFLOW) BEF ADV TO AFF.',
                'daily_inflow_name' => null,
                'inflow_total'    => ($before ? $before->inflow_total : 0) + ($beginning ? $beginning->inflow_total : 0),
            ];
        });

        $inflowOutflowAff = $grouped->map(function ($group) use ($endingCashBeforeCapex) {
            $company = $group[0]->company_name;
            $week    = $group[0]->week_no;
            $beforeCapex   = $endingCashBeforeCapex->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $capexSubtotal = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'CAPEX') {
                    $capexSubtotal = (float) $row->inflow_total;
                }
            }
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'INFLOW / (OUTFLOW) BEF ADV TO AFF.',
                'daily_inflow_name' => null,
                'inflow_total'    => ($beforeCapex ? $beforeCapex->inflow_total : 0) - $capexSubtotal,
            ];
        });

        $beginningBalanceNontrade = $grouped->map(function ($group) {
            return (object) [
                'company_name'    => $group[0]->company_name,
                'week_no'         => $group[0]->week_no,
                'inflow_type_name'=> 'BEGINNING BALANCE - NONTRADE',
                'daily_inflow_name' => null,
                'inflow_total'    => 0,
            ];
        });

        $endingCashAff = $grouped->map(function ($group) use ($inflowOutflowAff, $beginningBalanceNontrade) {
            $company = $group[0]->company_name;
            $week    = $group[0]->week_no;
            $beforeAff = $inflowOutflowAff->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $beginning = $beginningBalanceNontrade->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'ENDING CASH AFTER AFFILIATE',
                'daily_inflow_name' => null,
                'inflow_total'    => ($beforeAff ? $beforeAff->inflow_total : 0) + ($beginning ? $beginning->inflow_total : 0),
            ];
        });

        $inflowOutflowStockholders = $grouped->map(function ($group) use ($inflowOutflowAff) {
            $company   = $group[0]->company_name;
            $week      = $group[0]->week_no;
            $befAdvAff = $inflowOutflowAff->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $netAdvAff = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET ADVANCES TO AFFILIATE') {
                    $netAdvAff = (float) $row->inflow_total;
                }
            }
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'INFLOW / (OUTFLOW) AFT ADV TO AFF.',
                'daily_inflow_name' => null,
                'inflow_total'    => ($befAdvAff ? $befAdvAff->inflow_total : 0) - $netAdvAff,
            ];
        });

        $beginningBalanceStockholders = $grouped->map(function ($group) {
            return (object) [
                'company_name'    => $group[0]->company_name,
                'week_no'         => $group[0]->week_no,
                'inflow_type_name'=> 'BEGINNING BALANCE - STOCKHOLDERS',
                'daily_inflow_name' => null,
                'inflow_total'    => 0,
            ];
        });

        $endingCashStockholders = $grouped->map(function ($group) use ($inflowOutflowStockholders, $beginningBalanceStockholders) {
            $company   = $group[0]->company_name;
            $week      = $group[0]->week_no;
            $afterStock = $inflowOutflowStockholders->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $beginning  = $beginningBalanceStockholders->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'ENDING CASH AFTER STOCKHOLDERS',
                'daily_inflow_name' => null,
                'inflow_total'    => ($afterStock ? $afterStock->inflow_total : 0) + ($beginning ? $beginning->inflow_total : 0),
            ];
        });

        $inflowOutflowFinanceTransactions = $grouped->map(function ($group) use ($inflowOutflowStockholders) {
            $company    = $group[0]->company_name;
            $week       = $group[0]->week_no;
            $befStock   = $inflowOutflowStockholders->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $netAdvStock = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET ADVANCES TO STOCKHOLDERS') {
                    $netAdvStock = (float) $row->inflow_total;
                }
            }
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'INFLOW / (OUTFLOW) FINANCE TRANSACTIONS',
                'daily_inflow_name' => null,
                'inflow_total'    => ($befStock ? $befStock->inflow_total : 0) - $netAdvStock,
            ];
        });

        $beginningBalanceFinanceTransactions = $grouped->map(function ($group) {
            return (object) [
                'company_name'    => $group[0]->company_name,
                'week_no'         => $group[0]->week_no,
                'inflow_type_name'=> 'BEGINNING BALANCE FINANCE TRANSACTIONS',
                'daily_inflow_name' => null,
                'inflow_total'    => 0,
            ];
        });

        $endingCashBeforeFinanceTransactions = $grouped->map(function ($group) use ($beginningBalanceFinanceTransactions, $inflowOutflowFinanceTransactions) {
            $company      = $group[0]->company_name;
            $week         = $group[0]->week_no;
            $beginBal     = $beginningBalanceFinanceTransactions->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $financeFlow  = $inflowOutflowFinanceTransactions->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'ENDING CASH BEFORE FINANCE TRANSACTIONS',
                'daily_inflow_name' => null,
                'inflow_total'    => ($beginBal ? $beginBal->inflow_total : 0) + ($financeFlow ? $financeFlow->inflow_total : 0),
            ];
        });

        $netCash = $grouped->map(function ($group) use ($inflowOutflowFinanceTransactions) {
            $company       = $group[0]->company_name;
            $week          = $group[0]->week_no;
            $beforeFinance = $inflowOutflowFinanceTransactions->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
            $netFinance    = 0;
            foreach ($group as $row) {
                if (strtoupper(trim($row->inflow_type_name)) === 'NET FINANCE TRANSACTIONS') {
                    $netFinance = (float) $row->inflow_total;
                }
            }
            return (object) [
                'company_name'    => $company,
                'week_no'         => $week,
                'inflow_type_name'=> 'NET CASH INFLOW',
                'daily_inflow_name' => null,
                'inflow_total'    => ($beforeFinance ? $beforeFinance->inflow_total : 0) - $netFinance,
            ];
        });

        // ── PER-COMPANY BEGINNING BALANCE ─────────────────────────────────────
        // Priority:
        //   1. Explicit entry for current month
        //   2. Explicit entry for previous month (carry-over)
        //   3. For January: December of previous year
        //   4. Default to 0
        $bbQuery = fn ($yr, $mo) => DB::table('beginning_balances')
            ->join('companies', 'companies.id', '=', 'beginning_balances.company')
            ->where('beginning_balances.year', $yr)
            ->where('beginning_balances.month', $mo)
            ->select('companies.company as company_name', 'beginning_balances.amount')
            ->get()
            ->keyBy('company_name');

        $bbFromDB = $bbQuery($monthFilter->year, $monthFilter->month);

        if ($bbFromDB->isEmpty()) {
            $prevYear  = $monthFilter->month === 1 ? $monthFilter->year - 1 : $monthFilter->year;
            $prevMonth = $monthFilter->month === 1 ? 12 : $monthFilter->month - 1;
            $bbFromDB  = $bbQuery($prevYear, $prevMonth);
        }

        // Label: last calendar day before this month, e.g. "BEGINNING BALANCE 01.31.26"
        $prevMonthEnd = $monthFilter->copy()->subDay();
        $bbLabel = 'BEGINNING BALANCE ' . $prevMonthEnd->format('m.d.y');

        // ── ENDING BALANCE with week-over-week carryover ──────────────────────
        // Week 1 beginning = DB balance; each subsequent week beginning = prior week ending balance.
        $allCompanies = $grouped->map(fn($g) => $g[0]->company_name)->unique()->sort()->values();
        $allWeeks     = $grouped->map(fn($g) => $g[0]->week_no)->unique()->sort()->values();

        // company_name → company_id lookup for saving
        $companyIdMap = $results->pluck('company_id', 'company_name');

        $beginningBalanceRows  = collect();
        $endingBalanceRows     = collect();
        $lastEndingByCompany   = [];   // tracks each company's final ending balance to persist

        foreach ($allCompanies as $company) {
            $prevEndingBalance = isset($bbFromDB[$company]) ? (float) $bbFromDB[$company]->amount : 0;

            foreach ($allWeeks as $week) {
                if (!$grouped->has("{$company}_{$week}")) continue;

                $beginningBalance = $prevEndingBalance;
                $netCashRow       = $netCash->first(fn($r) => $r->company_name === $company && $r->week_no === $week);
                $netCashAmount    = $netCashRow ? (float) $netCashRow->inflow_total : 0;
                $endingAmount     = $netCashAmount + $beginningBalance;

                $beginningBalanceRows->push((object) [
                    'company_name'      => $company,
                    'week_no'           => $week,
                    'inflow_type_name'  => $bbLabel,
                    'daily_inflow_name' => null,
                    'inflow_total'      => $beginningBalance,
                ]);

                $endingBalanceRows->push((object) [
                    'company_name'      => $company,
                    'week_no'           => $week,
                    'inflow_type_name'  => 'ENDING BALANCE',
                    'daily_inflow_name' => null,
                    'inflow_total'      => $endingAmount,
                ]);

                $prevEndingBalance             = $endingAmount;
                $lastEndingByCompany[$company] = $endingAmount;
            }
        }

        // ── Auto-save last week's ending balance as this month's beginning balance ──
        // Next month load will fall back to this record via the existing priority lookup.
        foreach ($lastEndingByCompany as $company => $amount) {
            $companyId = $companyIdMap[$company] ?? null;
            if (!$companyId) continue;

            DB::table('beginning_balances')->updateOrInsert(
                ['company' => $companyId, 'year' => $monthFilter->year, 'month' => $monthFilter->month],
                ['amount' => $amount, 'updated_at' => now()]
            );
        }

        $beginningBalanceRow = $beginningBalanceRows;
        $endingBalance       = $endingBalanceRows;

        return response()->json([
            'details'      => $results,
            'inflow_totals'=> $inflowTotals,

            'before_other_capex'       => $beforeOtherCapex,
            'beginning_balance_capex'  => $beginningBalanceCapex,
            'before_affiliate'         => $endingCashBeforeCapex,
            'after_capex'              => $inflowOutflowAff,

            'beginning_balance_nontrade'    => $beginningBalanceNontrade,
            'ending_cash_after_affiliate'   => $endingCashAff,

            'after_affiliate_advances'      => $inflowOutflowStockholders,
            'beginning_balance_stockholders'=> $beginningBalanceStockholders,
            'ending_cash_after_stockholders'=> $endingCashStockholders,

            'beginning_balance_finance_transactions'  => $beginningBalanceFinanceTransactions,
            'ending_cash_before_finance_transactions' => $endingCashBeforeFinanceTransactions,

            'finance_transactions' => $inflowOutflowFinanceTransactions,
            'net_cash'             => $netCash,
            'beginning_balance_row'=> $beginningBalanceRow,
            'beginning_balance_label' => $bbLabel,
            'ending_balance'       => $endingBalance,
        ]);
    }
}
