<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Component\Console\Question\Question;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getModelLabel(): string
    {
        return __('Service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Services');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->translateLabel(),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->fileAttachmentsVisibility('public')
                    ->columnSpanFull()
                    ->translateLabel(),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->helperText(__('Default duration of appointment'))
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('minimum_cancel_hours')
                    ->numeric()
                    ->translateLabel()
                    ->helperText(__('The amount of hours the contact is able to cancel the appointment')),
                Forms\Components\Toggle::make('active')
                    ->translateLabel()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('duration')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('minimum_cancel_hours')
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('active')
                    ->icon(fn (int $state): string => match ($state) {
                        0 => 'heroicon-o-no-symbol',
                        1 => 'heroicon-o-check',
                    })
                    ->translateLabel(),
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
                RelationManagers\QuestionsRelationManager::class,
                RelationManagers\TimesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
