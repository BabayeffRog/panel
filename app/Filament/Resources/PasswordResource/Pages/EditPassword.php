<?php

namespace App\Filament\Resources\PasswordResource\Pages;

use App\Filament\Resources\PasswordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPassword extends EditRecord
{
    protected static string $resource = PasswordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
