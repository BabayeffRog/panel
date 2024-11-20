<?php

namespace App\Filament\Resources\WorkFieldResource\Pages;

use App\Filament\Resources\WorkFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkField extends EditRecord
{
    protected static string $resource = WorkFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
