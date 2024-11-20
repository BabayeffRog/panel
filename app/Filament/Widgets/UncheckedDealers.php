<?php

namespace App\Filament\Widgets;

use App\Models\Dealer;
use App\Models\DealerCheck;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UncheckedDealers extends BaseWidget
{
    protected static ?string $heading = 'Bayiler ve Kontrol Detayları';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dealer::query()
                    ->addSelect([
                        'last_check_date' => DB::table('dealer_checks')
                            ->select('checked_at')
                            ->whereColumn('dealer_checks.dealer_id', 'dealers.id')
                            ->latest('checked_at')
                            ->limit(1)
                    ])
                    ->where(function ($query) {
                        $query->whereNull('last_checked_at')
                            ->orWhere('last_checked_at', '<', Carbon::now()->subDays(3));
                    })
                    ->orderByDesc('last_check_date') // Sanal sütun olan last_check_date’e göre sırala
            )
            ->columns([
                Tables\Columns\TextColumn::make('panel_name')
                    ->label('Panel Adı'),

                Tables\Columns\TextColumn::make('last_checked_at')
                    ->label('Son Kontrol Tarihi')
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
                Action::make('kontrol_edildi')
                    ->label('Kontrol Et')
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

                Action::make('son_kontroller')
                    ->label('Son Kontroller')
                    ->color('secondary')
                    ->icon('heroicon-o-clock')
                    ->modalHeading('Son Kontrol Geçmişi')
                    ->modalWidth('lg')
                    ->form([
                        Repeater::make('kontrol_gecmisi')
                            ->label('Kontrol Geçmişi')
                            ->schema([
                                TextInput::make('checked_by')->label('Kontrol Eden')->disabled(),
                                TextInput::make('checked_at')->label('Kontrol Tarihi')->disabled(),
                                TextInput::make('note')->label('Not')->disabled(),
                            ])
                            ->disableItemCreation(),
                    ])
                    ->mountUsing(function ($form, $record) {
                        $kontroller = DealerCheck::where('dealer_id', $record->id)
                            ->orderByDesc('checked_at')
                            ->get()
                            ->map(function ($check) {
                                return [
                                    'checked_by' => optional($check->checkedBy)->name,
                                    'checked_at' => $check->checked_at->format('Y-m-d H:i'),
                                    'note' => $check->note,
                                ];
                            });

                        $form->fill([
                            'kontrol_gecmisi' => $kontroller,
                        ]);
                    }),
            ]);
    }
}
