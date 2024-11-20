<?php
namespace App\Enums;

enum WorkField: string
{
    case Yayinci = 'yayinci';
    case Telegramci = 'telegramci';
    case Instagramci = 'instagramci';
    case Seo = 'seo';
    case Sms = 'sms';

    // Bütün dəyərləri qaytaran metod
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    // Etiketlər üçün
    public static function labels(): array
    {
        return [
            self::Yayinci->value => 'Yayıncı',
            self::Telegramci->value => 'Telegramçı',
            self::Instagramci->value => 'Instagramçı',
            self::Seo->value => 'SEO',
            self::Sms->value => 'SMS',
        ];
    }

    // Rəngləri qaytaran metod
    public static function colors(): array
    {
        return [
            self::Yayinci->value => 'success', // yaşıl
            self::Telegramci->value => 'primary', // mavi
            self::Instagramci->value => 'pink', // çəhrayı
            self::Seo->value => 'warning', // sarı
            self::Sms->value => 'info', // göy
        ];
    }

    // İkonlar üçün
    public static function icons(): array
    {
        return [
            self::Yayinci->value => 'heroicon-o-briefcase', // İşarə: çanta
            self::Telegramci->value => 'heroicon-o-chat-alt', // İşarə: mesaj
            self::Instagramci->value => 'heroicon-o-camera', // İşarə: kamera
            self::Seo->value => 'heroicon-o-search', // İşarə: axtarış
            self::Sms->value => 'heroicon-o-mail', // İşarə: məktub
        ];
    }

    // Rəngə görə getter
    public function color(): string
    {
        return self::colors()[$this->value] ?? 'secondary';
    }

    // İkona görə getter
    public function icon(): string
    {
        return self::icons()[$this->value] ?? 'heroicon-o-question-mark-circle';
    }
}
