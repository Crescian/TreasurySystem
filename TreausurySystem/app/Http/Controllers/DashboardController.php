<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Daily treasury summary: inflow, outflow, net, transaction count for a given date.
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $inflow = (float) Logs::where('date', $date)
            ->where('flow_type', 'Inflow')
            ->sum('amount');

        $outflow = (float) Logs::where('date', $date)
            ->where('flow_type', 'Outflow')
            ->sum('amount');

        $count = (int) Logs::where('date', $date)->count();

        return response()->json([
            'inflow' => $inflow,
            'outflow' => $outflow,
            'net' => $inflow - $outflow,
            'count' => $count,
        ]);
    }
}
