<?php

namespace App\Exports;

use App\Models\FoodItem;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FoodMenuExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $restaurantId = Auth::user()->restaurant->id;

        return FoodItem::with('category', 'styles')
            ->where('restaurant_id', $restaurantId)
            ->get()
            ->map(function ($item) {
                return [
                    'item_code' => $item->item_code,
                    'name' => $item->name,
                    'category' => optional($item->category)->name,
                    'style' => $item->styles->pluck('name')->join(', '),
                    'description' => $item->description,
                    'price' => $item->price,
                ];
            });
    }

    public function headings(): array
    {
        return ['Item Code', 'Name', 'Category', 'Style', 'Description', 'Price'];
    }
}
