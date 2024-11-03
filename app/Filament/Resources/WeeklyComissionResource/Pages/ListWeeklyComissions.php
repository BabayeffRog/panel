<?php

namespace App\Filament\Resources\WeeklyComissionResource\Pages;

use App\Filament\Resources\WeeklyComissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWeeklyComissions extends ListRecords
{
    protected static string $resource = WeeklyComissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
