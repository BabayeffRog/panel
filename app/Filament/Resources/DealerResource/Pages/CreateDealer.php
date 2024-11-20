<?php

namespace App\Filament\Resources\DealerResource\Pages;

use App\Filament\Resources\DealerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDealer extends CreateRecord
{
    protected static string $resource = DealerResource::class;

    protected function getRedirectUrl(): string#
    {
        return static::getResource()::getUrl('index');
    }
}
