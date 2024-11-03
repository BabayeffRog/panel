<?php

namespace App\Filament\Widgets;

use App\Models\Dealer;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class UncheckedDealers extends BaseWidget
{
    protected static ?string $heading = '3 Gündür Kontrol Sağlanmıyor';
    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dealer::query()
                    ->where(function ($query) {
                        $query->whereNull('last_checked_at')
                            ->orWhere('last_checked_at', '<', Carbon::now()->subDays(3));
                    })
                    ->orderBy('last_checked_at', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('panel_name')
                    ->label('Panel Adı'),

                Tables\Columns\TextColumn::make('last_checked_at')
                    ->label('Son Kontrol Tarixi')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('workLinks')
                    ->label('Çalışma Alanları')
                    ->icon('heroicon-o-link')
                    ->modalHeading('Çalışma Alanları')
                    ->modalWidth('lg')
                    ->form([
                        Repeater::make('work_links')
                            ->label('Çalışma Alanları')
                            ->schema([
                                TextInput::make('field_url')->label('Alan Linki')->disabled(),
                            ])
                            ->disableItemCreation(),
                    ])
                    ->mountUsing(function ($form, $record) {
                        $form->fill([
                            'work_links' => $record->work_links,
                        ]);
                    }),
                Tables\Actions\ViewAction::make()
                    ->label('Detallar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Bayi Detalları')
                    ->modalWidth('lg')
                    ->form([
                        TextInput::make('panel_name')
                            ->label('Panel Adı')
                            ->disabled(),

                        TextInput::make('commission_account')
                            ->label('Komisyon Hesabı')
                            ->disabled(),

                        TextInput::make('test_account')
                            ->label('Test Hesabı')
                            ->disabled(),

                        TextInput::make('referral_number')
                            ->label('Ref Numarası')
                            ->disabled(),
                    ])
                    ->mountUsing(function ($form, $record) {
                        $form->fill([
                            'panel_name' => $record->panel_name,
                            'commission_account' => $record->commission_account,
                            'test_account' => $record->test_account,
                            'referral_number' => $record->referral_number,
                        ]);
                    }),
            ]);
    }
}
