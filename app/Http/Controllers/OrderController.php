<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function approveBulk(Request $request)
    {
        $ids = $request->input('ids');
        if (! is_array($ids)) {
            return response()->json(['success' => false]);
        }

        \App\Models\Order::whereIn('id', $ids)->update(['status' => 'Approved']);

        return response()->json(['success' => 'Selected orders approved successfully!']);
    }

    public function bulkPrepare(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Prepared',
            'prepared_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function bulkDeliver(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Delivered',
            'delivered_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function bulkComplete(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Completed',
            'completed_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}
