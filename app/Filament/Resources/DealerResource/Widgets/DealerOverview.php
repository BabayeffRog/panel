<?php

namespace App\Filament\Resources\DealerResource\Widgets;

use App\Models\Dealer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class DealerOverview extends BaseWidget
{
    protected function getCards(): array
    {
        // Bütün bayilər
        $totalBayiler = Dealer::count();

        // Həftə içində yaradılan bayilər
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekBayiler = Dealer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        // Ay içində yaradılan bayilər
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthBayiler = Dealer::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        return [
            Card::make('Tüm Bayiler', $totalBayiler)
                ->description('Toplam bayi sayısı')
                ->descriptionIcon('heroicon-o-rectangle-stack'),

            Card::make('Hafta İçinde Oluşan Bayiler', $weekBayiler)
                ->description('Bu hafta eklenen bayiler')
                ->descriptionIcon('heroicon-o-calendar'),

            Card::make('Ay İçinde Oluşan Bayiler', $monthBayiler)
                ->description('Bu ay eklenen bayiler')
                ->descriptionIcon('heroicon-o-calendar'),
        ];
    }
}
