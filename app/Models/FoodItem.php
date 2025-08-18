<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'category_id',
        'name',
        'description',
        'picture',
        'video',
        'price',
        'restaurant_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'picture' => 'array'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function styles()
    {
        return $this->belongsToMany(FoodStyle::class, 'food_item_food_style');
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id');
    }
}
