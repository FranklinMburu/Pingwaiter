<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worker;

class WorkerController extends Controller
{
    public function index()
    {
        return view('workers.index');
    }

    public function edit($id)
    {
        $worker = Worker::where('id', $id)
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->first();

        if (! $worker) {
            return response()->json(['error' => 'Worker not found.'], 404);
        }

        return response()->json(['worker' => $worker]);
    }

    public function getworkers()
    {
        $worker = Worker::select('*')
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->orderBy('created_at', 'desc');

        return datatables()->of($worker)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('workers.partials.buttons', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete($id)
    {
        $worker = Worker::find($id);

        $user = User::where('email', $worker->email)->delete();
        $workerDeleted = $worker->delete();

        return response()->json(['success' => 'Worker and associated user deleted successfully!']);
    }
}
