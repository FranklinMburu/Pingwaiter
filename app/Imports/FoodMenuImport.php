<?php

namespace App\Imports;

use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodStyle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class FoodMenuImport implements SkipsEmptyRows, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected $restaurantId;

    public function __construct($restaurantId)
    {
        $this->restaurantId = $restaurantId;
    }

    public function model(array $row)
    {
        $restaurantId = Auth::user()->restaurant->id;

        $category = FoodCategory::firstOrCreate(
            ['name' => $row['category']],
            ['restaurant_id' => $this->restaurantId]
        );

        $foodItem = new FoodItem([
            'item_code' => $row['item_code'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'category_id' => $category->id,
            'restaurant_id' => $restaurantId,
        ]);

        $foodItem->save();

        // Attach styles
        if (! empty($row['style'])) {
            $styleNames = array_map('trim', explode('|', $row['style']));
            $styleIds = FoodStyle::whereIn('name', $styleNames)
                ->where('restaurant_id', $restaurantId)
                ->pluck('id')
                ->toArray();
            $foodItem->styles()->sync($styleIds);
        }

        return $foodItem;
    }

    public function rules(): array
    {
        return [
            '*.item_code' => 'required|string|max:100',
            '*.name' => 'required|string',
            '*.category' => 'required|string',
            '*.price' => 'required|numeric|min:0',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('Import failure', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }
    }
}
