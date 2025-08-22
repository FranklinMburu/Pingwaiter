<?php

namespace App\Helpers;


use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Cache;

class RestaurantHelper
{
    public static function getSettings(): ?RestaurantSetting
    {
        return Cache::remember('restaurant_settings', 3600, function () {
            return RestaurantSetting::first();
        });
    }
    public static function getName(): string
    {
        $settings = self::getSettings();
        return $settings && $settings->name ? $settings->name : 'Restaurant Management System';
    }
}
