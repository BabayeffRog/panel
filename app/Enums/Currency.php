<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case TL = 'TL';
    case EUR = 'EUR';

    public static function values(): array
    {
        return array_column(Currency::cases(), 'value');
    }

    // Labels for displaying in forms or views
    public static function labels(): array
    {
        return [
            self::USD->value => 'US Dollar',
            self::TL->value => 'Türk Lirası',
            self::EUR->value => 'Euro',
        ];
    }
}
