<?php

namespace App\Filament\Resources\MonthlyPaymentResource\Pages;

use App\Filament\Resources\MonthlyPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMonthlyPayment extends CreateRecord
{
    protected static string $resource = MonthlyPaymentResource::class;
}
