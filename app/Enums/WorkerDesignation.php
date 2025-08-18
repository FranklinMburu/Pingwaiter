<?php

namespace App\Enums;

enum WorkerDesignation: string
{
    case ADMIN = 'admin';

    case RESTAURANT = 'restaurant';
    case CASHIER = 'cashier';
    case COOK = 'cook';
    case WAITER = 'waiter';
    case CUSTOMER = 'customer';
    case OTHERS = 'others';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
