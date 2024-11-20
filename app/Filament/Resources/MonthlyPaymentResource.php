<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Enums\Payment;
use App\Filament\Resources\MonthlyPaymentResource\Pages;
use App\Filament\Resources\MonthlyPaymentResource\RelationManagers;
use App\Models\Dealer;
use App\Models\MonthlyPayment;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class MonthlyPaymentResource extends Resource
{
    protected static ?string $model = MonthlyPayment::class;
    protected static ?string $label = 'Aylık Sabit Ödenişler';
    protected static ?string $pluralLabel = 'Aylık Sabitler';
    protected static ?string $modelLabel = 'Sabit Ödeme';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dealer_id')
                    ->label('Bayi')
                    ->relationship('dealer', 'panel_name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Dealer seçildikdən sonra onun payment məlumatlarını alırıq
                        $dealer = Dealer::find($state);

                        if ($dealer) {
                            // JSON-dan ilk payment_address və payment_method dəyərlərini çıxarırıq
                            $set('payment_address', $dealer->payment_address ?? null);
                            $set('payment_method', $dealer->payment_method[0] ?? null);
                            $set('amount_due', $dealer->fixed_contract_price);
                        }
                    }),
                Forms\Components\TextInput::make('amount_due')
                    ->numeric()
                    ->required()
                    ->label('Gönderilecek Tutar'),
                Forms\Components\Select::make('currency')
                    ->options(Currency::labels())
                    ->default(Currency::TL->value)
                    ->label('Para Birimi'),

                Forms\Components\TextInput::make('payment_address')
                    ->label('Payment Address')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->options(Payment::labels()) // Use labels for display
                    ->default(Payment::TRC20->value) // Set the default value to TRC20 (without dash)
                    ->label('Ödeme Yöntemi')
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->label('Not')
                    ->nullable(),
                Forms\Components\Toggle::make('is_sent')
                    ->label('Payment Sent')
                    ->default(false),
                Forms\Components\DateTimePicker::make('sent_at')
                    ->label('Gönderilme Tarihi')
                    ->default(now()),
                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id()),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dealer.panel_name')
                    ->label('Bayi Panel K/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_due')->label('Tutar'),
                Tables\Columns\TextColumn::make('currency')->label('Para Birimi'),
                Tables\Columns\TextColumn::make('payment_method')->label('Ödeme Yöntemi'),
                Tables\Columns\BooleanColumn::make('is_sent')
                    ->label('Gönderildi')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sent_at')->label('Sent At'),
                Tables\Columns\TextColumn::make('createdBy.name')->label('Ödemeni Oluşturdu'), // Show who created it
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMonthlyPayments::route('/'),
            'create' => Pages\CreateMonthlyPayment::route('/create'),
            'edit' => Pages\EditMonthlyPayment::route('/{record}/edit'),
        ];
    }
}
