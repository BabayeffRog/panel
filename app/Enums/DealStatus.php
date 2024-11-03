<?php
namespace App\Enums;

enum DealStatus: string
{
    case Anlasildi = 'Anlaşıldı';         // Deal confirmed
    case Beklemede = 'Beklemede';         // Waiting
    case AnlasmaIptal = 'Anlaşma İptal';  // Deal cancelled
    case Tamamlandi = 'Tamamlandı';       // Completed
    case OnayBekliyor = 'Onay Bekliyor';  // Awaiting approval
    case Dolandirici = 'Dolandırıcı';     // Fraudster

    public static function values(): array
    {
        return [
            self::Anlasildi->value,
            self::Beklemede->value,
            self::AnlasmaIptal->value,
            self::Tamamlandi->value,
            self::OnayBekliyor->value,
            self::Dolandirici->value,
        ];
    }

    public static function labels(): array
    {
        return [
            self::Anlasildi->value => 'Anlaşıldı',
            self::Beklemede->value => 'Beklemede',
            self::AnlasmaIptal->value => 'Anlaşma İptal',
            self::Tamamlandi->value => 'Tamamlandı',
            self::OnayBekliyor->value => 'Onay Bekliyor',
            self::Dolandirici->value => 'Dolandırıcı',
        ];
    }

    public static function colors(): array
    {
        return [
            self::Anlasildi->value => 'success',     // Green color for confirmed
            self::Beklemede->value => 'warning',     // Yellow color for waiting
            self::AnlasmaIptal->value => 'danger',   // Red color for cancelled
            self::Tamamlandi->value => 'success',    // Green color for completed
            self::OnayBekliyor->value => 'primary',  // Blue color for awaiting approval
            self::Dolandirici->value => 'danger',    // Red color for fraudster
        ];
    }

    public static function icons(): array
    {
        return [
            self::Anlasildi->value => 'heroicon-o-check-circle',  // Icon for confirmed
            self::Beklemede->value => 'heroicon-o-clock',         // Icon for waiting
            self::AnlasmaIptal->value => 'heroicon-o-x-circle',   // Icon for cancelled
            self::Tamamlandi->value => 'heroicon-o-check',        // Icon for completed
            self::OnayBekliyor->value => 'heroicon-o-shield-check',// Icon for awaiting approval
            self::Dolandirici->value => 'heroicon-o-exclamation', // Icon for fraudster
        ];
    }
}
