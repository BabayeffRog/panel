<?php

namespace App\Filament\Resources\DealerCommissionsResource\Pages;

use App\Filament\Resources\DealerCommissionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDealerCommissions extends ListRecords
{
    protected static string $resource = DealerCommissionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
