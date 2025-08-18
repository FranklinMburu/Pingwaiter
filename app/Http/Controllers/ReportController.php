<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function dailySales(Request $request)
    {

        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        $total = Order::whereDate('created_at', "$year-$month-$day")
            ->where('paid_status', '1')
            ->sum(DB::raw('price * quantity'));

        return response()->json(['total' => $total]);

    }

    public function monthlySales(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $total = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('paid_status', '1')
            ->sum(DB::raw('price * quantity'));

        return response()->json(['total' => $total]);
    }

    public function yearlySales(Request $request)
    {
        $year = $request->year;

        $total = Order::whereYear('created_at', $year)
            ->where('paid_status', '1')
            ->sum(DB::raw('price * quantity'));

        return response()->json(['total' => $total]);
    }
}
