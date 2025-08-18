<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Yajra\DataTables\DataTables;

class RewardController extends Controller
{
    public function index()
    {
        return view('rewards.index');
    }

    public function data()
    {
        $query = Reward::with('user')->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('customer_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->editColumn('email', function ($row) {
                return $row->user->email ?? 'N/A';
            })
            ->editColumn('date', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary">View</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
