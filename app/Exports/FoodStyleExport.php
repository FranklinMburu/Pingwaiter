<?php

namespace App\Exports;

use App\Models\FoodStyle;
use Maatwebsite\Excel\Concerns\FromCollection;

class FoodStyleExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FoodStyle::select('name')->get();
    }
}
