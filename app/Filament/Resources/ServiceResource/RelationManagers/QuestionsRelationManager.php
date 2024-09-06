<?php

declare(strict_types=1);

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Enums\QuestionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->live()
                    ->required()
                    ->options(QuestionType::class),

                Forms\Components\TextInput::make('hint')
                    ->hint(__('Displays below the question to provide additional context'))
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\TextInput::make('key')
                    ->hint(__('Used for integrations - not shown to the customer'))
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(64),

                Forms\Components\Toggle::make('required')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('order')->default(99),
                Forms\Components\Repeater::make('type_meta')
                    ->columnSpanFull()
                    ->hint(__('These options appear for types: checkbox, dropdown, radio'))
                    ->simple(Forms\Components\TextInput::make('value')
                        ->required(function (Forms\Get $get): bool {
                            return in_array($get('type'), ['toggle', 'text', 'textarea', '']);
                        }))
                    ->hidden(function (Forms\Get $get): bool {
                        return in_array($get('type'), ['toggle', 'text', 'textarea', '']);
                    })
                    ->addActionLabel(__('Add Option'))
                    ->reorderableWithButtons(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->reorderable('order')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('question'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('type_meta'),
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
