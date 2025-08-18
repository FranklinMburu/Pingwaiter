<?php

namespace App\Imports;

use App\Models\FoodCategory;
use Maatwebsite\Excel\Concerns\ToModel;

class FoodCategoryImport implements ToModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new FoodCategory([
            'name' => $row[0],
        ]);
    }
}
