<?php

namespace App\Filament\Resources\DealerResource\Pages;

use App\Filament\Resources\DealerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EditDealer extends EditRecord
{

    protected static string $resource = DealerResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
