<?php

namespace App\Filament\Resources\WorkFieldsResource\Pages;

use App\Filament\Resources\WorkFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkFields extends EditRecord
{
    protected static string $resource = WorkFieldsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
