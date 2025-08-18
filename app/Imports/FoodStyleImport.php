<?php

namespace App\Imports;

use App\Models\FoodStyle;
use Maatwebsite\Excel\Concerns\ToModel;

class FoodStyleImport implements ToModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new FoodStyle([
            'name' => $row[0],
        ]);
    }
}
