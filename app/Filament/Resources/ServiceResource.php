<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'underline',
                    ])
                    ->fileAttachmentsVisibility('public'),
                Forms\Components\RichEditor::make('terms')
                    ->hint(__('Displays as a required checkbox at the end of the appointment form.'))
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'italic',
                        'link',
                    ]),
                Forms\Components\Toggle::make('allow_user_selection'),
                Forms\Components\Toggle::make('all_day')
                    ->requiredWith('duration')
                    ->reactive()
                    ->afterStateUpdated(
                        fn ($state, callable $set) => $state ? $set('duration', null) : $set('duration', 'hidden')
                    ),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->requiredWith('all_day')
                    ->helperText(__('Default duration of appointment'))
                    ->required()->hidden(
                        fn ($get): bool => $get('all_day') == true
                    ),
                Forms\Components\TextInput::make('minimum_cancel_hours')
                    ->numeric()
                    ->helperText(__('The amount of hours the contact is able to cancel the appointment')),
                Forms\Components\Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('minimum_cancel_hours'),
                Tables\Columns\ToggleColumn::make('active'),
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
            RelationManagers\UsersRelationManager::class,
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
