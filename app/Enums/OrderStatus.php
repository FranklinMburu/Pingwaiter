<?php

namespace App\Enums;

enum OrderStatus: string
{
    case EDITABLE = 'Editable';
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case PREPARED = 'Prepared';
    case DELIVERED  = 'Delivered';
    case COMPLETED = 'Completed';


    public function label(): string
    {
        return match($this) {
            self::EDITABLE => 'Editable',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::PREPARED => 'Prepared',
            self::DELIVERED => 'Delivered',
            self::COMPLETED => 'Completed',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}