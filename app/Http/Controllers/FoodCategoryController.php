<?php

namespace App\Http\Controllers;

use App\Exports\FoodCategoryExport;
use App\Imports\FoodCategoryImport;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FoodCategoryController extends Controller
{
    public function index()
    {
        return view('food-categories.index');
    }

    public function getFoodCategories()
    {
        $categories = FoodCategory::where('restaurant_id', auth()->user()->restaurant_id)
            ->orderBy('name')
            ->select(['id', 'name']);

        return datatables()
            ->of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit = '<button data-id="'.$row->id.'" class="btn btn-sm btn-info edit-btn"><i class="fas fa-edit"></i></button>';
                $delete = '<button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>';

                return '<div class="flex items-center justify-center gap-2">'.$edit.$delete.'</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $category = FoodCategory::findOrFail($id);

        return response()->json($category);
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

        $foodCategory = $id == 'add' ? new FoodCategory : FoodCategory::findOrFail($id);
        $foodCategory->name = $request->name;
        $foodCategory->restaurant_id = auth()->user()->restaurant_id;
        $foodCategory->save();

        return response()->json([
            'success' => 'Food category '.($id == 'add' ? 'created' : 'updated').' successfully',
        ]);
    }

    public function delete($id)
    {
        $category = FoodCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Food category deleted successfully']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        Excel::import(new FoodCategoryImport, $request->file('file'));

        return response()->json(['success' => 'Food categories uploaded successfully.']);
    }

    public function export()
    {
        return Excel::download(new FoodCategoryExport, 'food_categories.xlsx');
    }
}
