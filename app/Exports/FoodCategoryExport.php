<?php

namespace App\Exports;

use App\Models\FoodCategory;
use Maatwebsite\Excel\Concerns\FromCollection;

class FoodCategoryExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return FoodCategory::select(['id', 'name'])->get();
    }
}
