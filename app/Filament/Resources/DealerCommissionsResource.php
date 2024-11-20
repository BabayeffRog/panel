<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Filament\Resources\DealerCommissionsResource\Pages;
use App\Models\Dealer;
use App\Models\WeeklyCommission;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DealerCommissionsResource extends Resource
{
    protected static ?string $model = Dealer::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Dealer Komisyonları';
    protected static ?string $siteStartDate = '2024-04-29'; // Saytın ilk başlama tarixi

    public static function table(Table $table): Table
    {
        $defaultWeeks = 5; // Default olaraq son 5 həftəni göstər
        return $table
            ->query(Dealer::query())
            ->columns(
                array_merge(
                    [
                        Tables\Columns\TextColumn::make('panel_name')
                            ->label('Panel Adı')
                            ->sortable()
                            ->searchable(),
                    ],
                    self::generateWeeklyCommissionColumns($defaultWeeks) // Son 5 həftə üçün sütunlar
                )
            )
            ->actions([
                Tables\Actions\Action::make('addCommission')
                    ->label(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'Eklendi' : 'Komisyon Ekle')
                    ->icon(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'heroicon-o-check-circle' : 'heroicon-o-plus-circle')
                    ->color(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'success' : 'warning')
                    ->disabled(fn ($record) => $record->hasCommissionForCurrentWeek())
                    ->modalHeading('Komisyon Ekle')
                    ->modalWidth('lg')
                    ->form([
                        DatePicker::make('week_start')
                            ->label('Hafta Başlanğıcı')
                            ->required()
                            ->default(now()->startOfWeek()),
                        DatePicker::make('week_end')
                            ->label('Hafta Sonu')
                            ->required()
                            ->default(now()->endOfWeek()),
                        TextInput::make('commission_amount')
                            ->label('Komisyon Tutarı')
                            ->required()
                            ->numeric(),
                        Select::make('currency')
                            ->label('Para Birimi')
                            ->options(Currency::labels())
                            ->nullable()
                            ->default(Currency::TL->value),
                        Textarea::make('note')
                            ->label('Qeyd')
                            ->nullable(),
                    ])
                    ->action(function ($record, $data) {
                        $record->commissions()->create([
                            'amount' => $data['commission_amount'],
                            'week_start' => $data['week_start'],
                            'week_end' => $data['week_end'],
                            'currency' => $data['currency'],
                            'note' => $data['note'],
                            'created_by' => auth()->id(),

                        ]);
                        // Komisyon əlavə edildikdən sonra son tarixi yeniləyirik
                        $record->update([
                            'last_weekly_commission' => now(),
                        ]);

                        // Siyahını yeniləyirik
                        return redirect(request()->header('Referer'));
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('num_weeks')
                    ->label('Həftə Seçimi')
                    ->options(self::generateWeekFilterOptions())
                    ->default($defaultWeeks)
                    ->query(function ($query, $state) {
                        return $query;
                    }),
            ]);
    }

    protected static function generateWeeklyCommissionColumns(int $numWeeks): array
    {
        $columns = [];
        $siteStartDate = Carbon::parse(self::$siteStartDate)->startOfWeek(); // Saytın başlama tarixi
        $currentWeek = Carbon::now()->startOfWeek(); // Cari həftə

        // Cari həftədən başlayaraq son $numWeeks qədər həftəni tərsinə göstərmək üçün dövr
        for ($i = 0; $i < $numWeeks; $i++) {
            // Həftənin başlanğıc və son tarixlərini əldə edirik
            $weekStart = $currentWeek->copy()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            // Başlanğıc tarixdən bu həftəyə qədər olan həftə sayını müəyyən edirik
            $weekNumber = $siteStartDate->diffInWeeks($weekStart) + 1;
            $weekLabel = "Hafta {$weekNumber} ¬ {$weekStart->format('d.m')} - {$weekEnd->format('d.m')}";

            // Komisyon məlumatını göstərmək üçün sütun əlavə edirik
            $columns[] = Tables\Columns\TextColumn::make("weekly_commissions.week_{$weekNumber}")
                ->label($weekLabel)
                ->getStateUsing(function ($record) use ($weekStart, $weekEnd) {
                    $commission = WeeklyCommission::where('dealer_id', $record->id)
                        ->whereBetween('week_start', [$weekStart, $weekEnd])
                        ->first();

                    if ($commission) {
                        // Əgər sifirlanıbsa, xüsusi mətn qaytar
                        return $commission->is_reset
                            ? 'Panel Sıfırlama'
                            : "{$commission->amount} {$commission->currency}";
                    }

                    return '-';
                });
        }

        return $columns;
    }



    protected static function generateWeekFilterOptions(): array
    {
        $startDate = Carbon::parse(self::$siteStartDate)->startOfWeek();
        $currentWeek = Carbon::now()->startOfWeek();
        $numWeeks = $currentWeek->diffInWeeks($startDate) + 1;

        $options = [];
        for ($i = 1; $i <= $numWeeks; $i++) {
            $options[$i] = "Son $i həftə";
        }

        return $options;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDealerCommissions::route('/'),
        ];
    }
}
