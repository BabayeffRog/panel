<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Enums\Payment;
use App\Filament\Resources\DealerResource\Pages;
use App\Filament\Resources\DealerResource\RelationManagers;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use App\Models\Dealer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

class DealerResource extends Resource
{
    protected static ?string $model = Dealer::class;
    protected static ?string $label = 'Bayiler';
    protected static ?string $pluralLabel = 'Bayiler';
    protected static ?string $modelLabel = 'Bayi';

    protected static ?string $recordTitleAttribute = 'panel_name';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Bayi bilgileri')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([

                            Forms\Components\TextInput::make('panel_name')
                                ->required()
                                ->label('Panel Kullanıcı adı')
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('commission_account')
                                ->label('Komisyon Hesabı')
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('test_account')
                                ->label('Test Kullanıcı Hesabı')
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('referral_number')
                                ->label('Ref Numarası')
                                ->required(),
                            Forms\Components\TextInput::make('work_field')
                                ->label('Çalışma alanı'),
                            Forms\Components\TextInput::make('skype_live')
                                ->label('Skype Live adresi')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('skype_group')
                                ->label('Skype Grup adresi')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('skype_name')
                                ->label('Skype Kayıtlı adı')
                                ->maxLength(255),

                        ])->columns(3),

                    Forms\Components\Wizard\Step::make('Anlaşma Bilgileri')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('Ödeme bilgileri, anlaşma tarihi')
                        ->schema([
                            // İlk üç komponenti birinci sıraya yerləşdiririk
                            Forms\Components\TextInput::make('fixed_contract_price')
                                ->label('Sabit Ödemesi')
                                ->required()
                                ->numeric()
                                ->default(0.00)
                                ->columnSpan(1), // İlk sütun

                            Forms\Components\Select::make('currency')
                                ->options(Currency::labels())
                                ->nullable()
                                ->default(Currency::TL->value)
                                ->label('Para Birimi')
                                ->columnSpan(1), // İkinci sütun

                            Forms\Components\TextInput::make('affiliate_commission')
                                ->label('Aff %')
                                ->required()
                                ->numeric()
                                ->default(1.00)
                                ->columnSpan(1), // Üçüncü sütun

                            // Digər komponentlər ard-arda davam edir
                            Forms\Components\DatePicker::make('contract_date')
                                ->label('Anlaşma Tarihi'),
                            Forms\Components\TextInput::make('payment_address')
                                ->label('Ödeme Adresi')
                                ->maxLength(255),
                            Forms\Components\Select::make('payment_method')
                                ->options(Payment::labels()) // Use labels for display
                                ->nullable()
                                ->default(Payment::TRC20->value) // Set the default value to TRC20 (without dash)
                                ->label('Ödeme Yöntemi')
                                ->required(),
                            Forms\Components\Textarea::make('contract_details')
                                ->label('Anlaşma Detayı')
                                ->columnSpanFull(),
                        ])
                        ->columns(3), // 3 sütunlu düzən yaradır


                    Forms\Components\Wizard\Step::make('Çalışma Alanları')
                        ->schema([
                            Forms\Components\Repeater::make('work_links')
                            ->label('Çalışma Alanları')
                                ->schema([
                                    Forms\Components\TextInput::make('field_url')
                                        ->label('Alan Linki')
                                        ->required(),
                                ])
                                ->collapsible() // İstəyə bağlı olaraq collapsible edə bilərsiniz
                                ->createItemButtonLabel('Çalışma Alanı Ekle')
                        ]),
                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('last_weekly_commission', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('panel_name')
                    ->label('Panel K/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commission_account')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Komisyon K/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('test_account')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Test K/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referral_number')
                    ->label('Ref №')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fixed_contract_price')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Sabit Ödeme')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('affiliate_commission')
                    ->label('Aff %')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_weekly_commission')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Son Komisyon Tarihi')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skype_live')
                    ->label('Skype Live Adresi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('skype_group')
                    ->label('Skype Grup Adresi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('skype_name')
                    ->label('Skype Adı')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_date')
                    ->label('Anlaşma Tarihi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_address')
                    ->label('Ödeme Adresi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('workLinks')
                    ->label('Çalışma Alanları')
                    ->color('warning')
                    ->icon('heroicon-o-link')
                    ->modalHeading('Çalışma Alanları')
                    ->modalWidth('lg')
                    ->form([
                        Forms\Components\Repeater::make('work_links')
                            ->label('Çalışma Alanları')
                            ->schema([
                                Forms\Components\TextInput::make('field_url')->label('Alan Linki')->disabled(), // Linklər burada disabled olacaq
                            ])
                            ->disableItemCreation() // Yeni item əlavə etməyi deaktiv edirik
                    ])
                    ->mountUsing(function ($form, $record) {
                        // Məlumatları forma ötürürük
                        $form->fill([
                            'work_links' => $record->work_links,
                        ]);
                    }),
                Tables\Actions\Action::make('addCommission')
                    ->label(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'Eklendi' : 'Komisyon Ekle')
                    ->icon(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'heroicon-o-check-circle' : 'heroicon-o-plus-circle')
                    ->color(fn ($record) => $record->hasCommissionForCurrentWeek() ? 'success' : 'warning')
                    ->disabled(fn ($record) => $record->hasCommissionForCurrentWeek())
                    ->modalHeading('Komisyon Ekle')
                    ->modalWidth('lg')
                    ->form([
                        Forms\Components\DatePicker::make('week_start')
                            ->label('Hafta Başlanğıcı')
                            ->required()
                            ->default(now()->startOfWeek()),
                        Forms\Components\DatePicker::make('week_end')
                            ->label('Hafta Sonu')
                            ->required()
                            ->default(now()->endOfWeek()),
                        Forms\Components\TextInput::make('commission_amount')
                            ->label('Komisyon Tutarı')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('currency')
                            ->label('Para Birimi')
                            ->options(Currency::labels())
                            ->nullable()
                            ->default(Currency::TL->value),
                        Forms\Components\Textarea::make('note')
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


                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                ActivityLogTimelineTableAction::make('Activity Log')
                ->label('Geçmiş')
                ->color('gray'),
            ])
            ->bulkActions([

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3) // 3 sütunlu düzən yarat
                ->schema([
                    // Genel Bilgiler
                    TextEntry::make('panel_name')
                        ->label('Panel Kullanıcı Adı')
                        ->icon('heroicon-o-user')
                        ->badge()
                        ->color('info'),

                    TextEntry::make('commission_account')
                        ->label('Komisyon Hesabı')
                        ->icon('heroicon-o-user'),

                    TextEntry::make('test_account')
                        ->label('Test Hesabı')
                        ->icon('heroicon-o-beaker'),

                    TextEntry::make('referral_number')
                        ->label('Referans Numarası')
                        ->badge()
                        ->color('success'),


                    // Anlaşma Bilgileri (Yan-yana Gruplar)
                    Group::make([
                        TextEntry::make('fixed_contract_price')
                            ->label('Sabit Ödeme')
                            ->icon('heroicon-o-credit-card')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('currency')
                            ->label('Para Birimi')
                            ->icon('heroicon-o-currency-dollar'),
                    ])->columns(2),

                    TextEntry::make('affiliate_commission')
                        ->label('Aff Komisyon %')
                        ->icon('heroicon-o-briefcase')
                        ->badge()
                        ->color('success'),

                    TextEntry::make('payment_address')
                        ->label('Ödeme Adresi'),

                    TextEntry::make('payment_method')
                        ->label('Ödeme Yöntemi')
                        ->icon('heroicon-o-credit-card')
                        ->badge()
                        ->color('success'),

                    // İletişim Bilgileri
                    TextEntry::make('skype_live')
                        ->icon('heroicon-o-link')
                        ->label('Skype Live Adresi'),

                    TextEntry::make('skype_group')
                        ->label('Skype Grup Adresi')
                        ->icon('heroicon-o-users'),

                    TextEntry::make('skype_name')
                        ->label('Skype Adı')
                        ->icon('heroicon-o-user-circle'),

                    // Not
                    TextEntry::make('contract_details')
                        ->label('Anlaşma Detayı'),
                ]),

                // Çalışma Alanları (Collapsible və default bağlı)
                Section::make('Çalışma Alanları')
                    ->collapsible()
                    ->collapsed() // Default olaraq bağlı halda görünməsi üçün
                    ->schema([
                        RepeatableEntry::make('work_links')
                            ->label('Çalışma Alanları Listesi')
                            ->schema([
                                TextEntry::make('field_url')
                                    ->label('Alan Linki')
                                    ->icon('heroicon-o-link')
                                    ->badge()
                                    ->color('secondary'),
                            ]),
                    ]),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //ActivitylogRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [

        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewDealer::class,
            Pages\EditDealer::class,
            Pages\ManageWeeklyCommission::class,
        ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDealers::route('/'),
            'create' => Pages\CreateDealer::route('/create'),
            'edit' => Pages\EditDealer::route('/{record}/edit'),
            'view' => Pages\ViewDealer::route('/{record}/view'),
            'manageWeeklyCommission' => Pages\ManageWeeklyCommission::route('/{record}/manage-weekly-commission'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['panel_name', 'referral_number', 'skype_live', 'commission_account', 'test_account', 'skype_group', 'skype_name', 'work_field', 'payment_address', 'contract_details'];
    }

    /**
     * Bu metod global axtarış zamanı JSON sahələri daxil olmaqla bütün əsas sahələrdə axtarış aparır.
     */
    public static function getGlobalSearchResultQuery(string $search): Builder
    {
        return static::getModel()::query()
            ->select(['dealers.*'])
            ->orWhere('panel_name', 'like', '%' . $search . '%')
            ->orWhere('referral_number', 'like', '%' . $search . '%')
            ->orWhere('skype_live', 'like', '%' . $search . '%')
            ->orWhere('commission_account', 'like', '%' . $search . '%')
            ->orWhere('test_account', 'like', '%' . $search . '%')
            ->orWhere('skype_group', 'like', '%' . $search . '%')
            ->orWhere('skype_name', 'like', '%' . $search . '%')
            ->orWhere('work_field', 'like', '%' . $search . '%')
            ->orWhere('contract_details', 'like', '%' . $search . '%')
            ->orWhereJsonContains('work_links->field_url', $search); // JSON içində axtarış
    }

}
