<?php

namespace App\Filament\Resources\DealerResource\Pages;

use App\Filament\Resources\DealerResource;
use App\Models\WeeklyCommission;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Illuminate\Database\Eloquent\Builder;

class ManageWeeklyCommission extends ManageRelatedRecords
{
    protected static string $resource = DealerResource::class;
    protected static string $relationship = 'commissions';

    protected function getTableHeading(): string
    {
        return 'Keçmiş Həftəlik Komisyonlar';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => WeeklyCommission::query()->where('dealer_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('week_start')
                    ->label('Hafta Başlanğıcı')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('week_end')
                    ->label('Hafta Sonu')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Komisyon Miktarı')
                    ->money('currency')
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Para birimi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('note')
                    ->label('Not')
                    ->wrap(),
            ])
            ->filters([
                // Əlavə olaraq xüsusi filtr ehtiyacın varsa burada yarada bilərsən
            ])

            ->actions([
                //Actions\EditAction::make(),
                //Actions\DeleteAction::make(),
            ])
            ->defaultSort('week_start', 'desc'); // Sıralamanı həftə başlanğıcına görə müəyyən et
    }
}
