<?php

namespace App\Filament\Resources\WeeklyComissionResource\Pages;

use App\Filament\Resources\WeeklyComissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWeeklyComission extends CreateRecord
{
    protected static string $resource = WeeklyComissionResource::class;

    protected function getRedirectUrl(): string#
    {
        return static::getResource()::getUrl('index');
    }
}
