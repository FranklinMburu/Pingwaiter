<?php

namespace App\Http\Controllers;

class TableCallController extends Controller
{
    public function details($id)
    {
        $table = Table::with('restaurant')->findOrFail($id);

        return view('table.details', compact('table'));
    }
}
