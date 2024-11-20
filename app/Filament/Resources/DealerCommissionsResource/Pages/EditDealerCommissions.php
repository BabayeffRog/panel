<?php

namespace App\Filament\Resources\DealerCommissionsResource\Pages;

use App\Filament\Resources\DealerCommissionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDealerCommissions extends EditRecord
{
    protected static string $resource = DealerCommissionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
