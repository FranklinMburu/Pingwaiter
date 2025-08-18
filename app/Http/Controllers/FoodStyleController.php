<?php

namespace App\Http\Controllers;

use App\Exports\FoodStyleExport;
use App\Imports\FoodStyleImport;
use App\Models\FoodStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FoodStyleController extends Controller
{
    public function index()
    {
        return view('food-style.index');
    }

    public function getFoodStyles()
    {
        $styles = FoodStyle::select('*')
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->orderBy('created_at', 'desc');

        return datatables()->of($styles)
            ->addIndexColumn()
            ->make(true);
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $foodStyle = $id == 'add' ? new FoodStyle : FoodStyle::findOrFail($id);
        $foodStyle->name = $request->name;
        $foodStyle->restaurant_id = auth()->user()->restaurant->id;
        $foodStyle->save();

        return response()->json([
            'success' => 'Food style '.($id == 'add' ? 'created' : 'updated').' successfully',
        ]);
    }

    public function edit($id)
    {
        $style = FoodStyle::where('restaurant_id', auth()->user()->restaurant->id)
            ->find($id);

        if ($style) {
            return response()->json(['style' => $style]);
        }

        return response()->json(['error' => 'Food style not found'], 404);
    }

    public function delete($id)
    {
        $style = FoodStyle::where('restaurant_id', auth()->user()->restaurant->id)
            ->find($id);

        if (! $style) {
            return response()->json(['error' => 'Food style not found'], 404);
        }

        $delete = $style->delete();
        if ($delete) {
            return response()->json(['success' => 'Food Style deleted successfully!']);
        }

        return response()->json(['error' => 'Failed to delete food style'], 500);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new FoodStyleImport, $request->file('file'));

        return response()->json(['success' => 'Food styles uploaded successfully.']);
    }

    public function export()
    {
        return Excel::download(new FoodStyleExport, 'food_styles.xlsx');
    }
}
