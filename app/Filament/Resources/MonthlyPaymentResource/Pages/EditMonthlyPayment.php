<?php

namespace App\Filament\Resources\MonthlyPaymentResource\Pages;

use App\Filament\Resources\MonthlyPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyPayment extends EditRecord
{
    protected static string $resource = MonthlyPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
