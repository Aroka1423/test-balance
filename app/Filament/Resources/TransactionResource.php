<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('user_id')
                                                ->relationship('user', 'email'),

                Forms\Components\TextInput::make('amount')
                                          ->type('number')
                                          ->step('0.01')
                                          ->required(),

                Forms\Components\Select::make('type')
                                       ->options([
                                           'credit' => 'Credit',
                                           'debit'  => 'Debit',
                                       ])
                                       ->required(),

                Forms\Components\Textarea::make('description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Column::make('user.email')
                                     ->label('User Email')
                                     ->searchable()
                                     ->sortable(),

                Tables\Columns\Column::make('amount')
                                     ->label('Amount'),

                Tables\Columns\Column::make('type')
                                     ->label('Type'),

                Tables\Columns\Column::make('description')
                                     ->label('Description'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            'user',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
