<?php

namespace App\Filament\Resources\PasswordResource\Pages;

use App\Filament\Resources\PasswordResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePassword extends CreateRecord
{
    protected static string $resource = PasswordResource::class;
    protected function getRedirectUrl(): string#
    {
        return static::getResource()::getUrl('index');
    }
}
