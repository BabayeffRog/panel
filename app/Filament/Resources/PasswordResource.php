<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasswordResource\Pages;
use App\Filament\Resources\PasswordResource\RelationManagers;
use App\Models\Password;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasswordResource extends Resource
{
    protected static ?string $model = Password::class;
    protected static ?string $label = 'Şifreler';
    protected static ?string $pluralLabel = 'Şifreler';
    protected static ?string $modelLabel = 'Şifre';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category'),
                Forms\Components\TextInput::make('login'),
                Forms\Components\TextInput::make('password'),
                Forms\Components\Textarea::make('note')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                ->searchable(),
                Tables\Columns\TextColumn::make('login')
                    ->searchable(),
                Tables\Columns\TextColumn::make('password')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPasswords::route('/'),
            'create' => Pages\CreatePassword::route('/create'),
            'edit' => Pages\EditPassword::route('/{record}/edit'),
        ];
    }
}
