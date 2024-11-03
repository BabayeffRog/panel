<?php

namespace App\Filament\Resources\MonthlyPaymentResource\Pages;

use App\Filament\Resources\MonthlyPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyPayments extends ListRecords
{
    protected static string $resource = MonthlyPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
