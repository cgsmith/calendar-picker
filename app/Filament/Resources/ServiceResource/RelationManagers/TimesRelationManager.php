<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Enums\DayOfWeek;
use App\Enums\ServiceTimeType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimesRelationManager extends RelationManager
{
    protected static string $relationship = 'times';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->required()
                    ->options(DayOfWeek::class)
                    ->translateLabel(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(ServiceTimeType::class)
                    ->translateLabel(),
                Forms\Components\TextInput::make('hour')
                    ->required()
                    ->integer()
                    ->translateLabel(),
                Forms\Components\TextInput::make('minute')
                    ->required()
                    ->integer()
                    ->translateLabel(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day_of_week')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('hour')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('minute')
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
