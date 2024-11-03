<?php
namespace App\Enums;

enum Payment: string
{
    case Papara = 'Papara';
    case TRC20 = 'TRC20'; // Corrected to TRC20 (no dash)
    case PayFix = 'PayFix';

    public static function values(): array
    {
        return [
            self::Papara->value,
            self::TRC20->value, // Updated to TRC20
            self::PayFix->value,
        ];
    }

    public static function labels(): array
    {
        return [
            self::Papara->value => 'Papara',
            self::TRC20->value => 'TRC20',
            self::PayFix->value => 'PayFix',
        ];
    }
}
