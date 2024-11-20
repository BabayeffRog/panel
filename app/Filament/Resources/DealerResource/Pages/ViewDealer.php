<?php

namespace App\Filament\Resources\DealerResource\Pages;

use App\Filament\Resources\DealerResource;
use App\Models\DealerCheck;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ViewDealer extends ViewRecord
{
    protected static string $resource = DealerResource::class;

    // Son kontrol tarixi və kontrol edən şəxsi göstərmək üçün bir funksiya
    protected function getLastCheckInfo()
    {
        $lastCheck = DealerCheck::where('dealer_id', $this->record->id)
            ->latest('checked_at')
            ->first();

        if ($lastCheck) {
            return [
                'date' => Carbon::parse($lastCheck->checked_at)->format('Y-m-d H:i'),
                'user' => $lastCheck->user->name ?? 'Silinmiş',
            ];
        }

        return [
            'date' => 'Yok',
            'user' => 'YoK',
        ];
    }

    // Kontrol edildikdə bir əməliyyat əlavə edin
    protected function getHeaderActions(): array
    {
        $lastCheckInfo = $this->getLastCheckInfo();

        return [
            Actions\Action::make('kontrol_edildi')
                ->label('Kontrol Sağla')
                ->color('warning')
                ->icon('heroicon-o-check-circle')
                ->modalHeading('Bayi Kontrolü')
                ->modalSubheading('Bu bayiyi kontrol ettiğinizden emin misiniz? Aşağıya not bırakabilirsiniz.')
                ->modalWidth('lg')
                ->form([
                    TextInput::make('note')
                        ->label('Not')
                        ->placeholder('Kontrol ile ilgili notunuzu buraya girin...')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    DealerCheck::create([
                        'dealer_id' => $record->id,
                        'checked_by' => Auth::id(),
                        'checked_at' => now(),
                        'note' => $data['note'],
                    ]);

                    // `last_checked_at` alanını güncelleme
                    $record->update([
                        'last_checked_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Bayi kontrol edildi. İyi çalışmalar!')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('last_checked_info')
                ->label("Son Kontrol: {$lastCheckInfo['date']} ({$lastCheckInfo['user']})")
                ->color('gray')
                ->disabled(), // Bu düymə kliklənə bilməz, yalnız məlumat göstərəcək
        ];
    }
}
