<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Filament\Resources\WeeklyComissionResource\Pages;
use App\Filament\Resources\WeeklyComissionResource\RelationManagers;
use App\Models\Dealer;
use App\Models\WeeklyCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WeeklyComissionResource extends Resource
{
    protected static ?string $model = WeeklyCommission::class;
    protected static ?string $label = 'Komisyon Aktarımları';
    protected static ?string $pluralLabel = 'Haftalık Komisyonlar';
    protected static ?string $modelLabel = 'Haftalık Komisyon';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dealer_id')
                    ->label('Bayi')
                    ->relationship('dealer', 'panel_name')
                    ->required()
                    ->searchable()
                    ->columnSpan(1),
                Forms\Components\DatePicker::make('week_start')
                    ->default(now()->startOfWeek())
                    ->required(),
                Forms\Components\DatePicker::make('week_end')
                    ->default(now()->endOfWeek())
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->columnSpan(1),

                Forms\Components\Textarea::make('note')

                    ->columnSpanFull(),


                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id()),
                Forms\Components\Toggle::make('is_reset')
                    ->label('Panel Sıfırlama')
                    ->default(false),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dealer.panel_name')
                    ->label('Bayi Panel K/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dealer.skype_live')
                    ->label('Live Skype')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Tutar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Para Birimi'),
                Tables\Columns\TextColumn::make('week_start')
                    ->label('Hafta başlanğıcı')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('week_end')
                    ->label('Hafta Sonu')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklyComissions::route('/'),
            'create' => Pages\CreateWeeklyComission::route('/create'),
            'edit' => Pages\EditWeeklyComission::route('/{record}/edit'),
        ];
    }
}
