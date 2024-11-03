<?php

namespace App\Filament\Resources\WeeklyComissionResource\Pages;

use App\Filament\Resources\WeeklyComissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeeklyComission extends EditRecord
{
    protected static string $resource = WeeklyComissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
