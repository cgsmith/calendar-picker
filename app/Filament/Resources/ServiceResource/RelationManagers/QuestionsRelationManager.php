<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Enums\QuestionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),

                Forms\Components\Select::make('type')
                    ->translateLabel()
                    ->options(QuestionType::class),

                Forms\Components\TextInput::make('hint')
                    ->hint(__('Displays below the question to provide additional context'))
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->translateLabel(),

                Forms\Components\Toggle::make('required')
                    ->columnSpanFull()
                    ->translateLabel(),
                Forms\Components\Hidden::make('order')->default(99),
                Forms\Components\Repeater::make('type_meta')
                    ->columnSpanFull()
                    ->hint(__('These options appear for types: checkbox, dropdown, radio'))
                    ->simple(Forms\Components\TextInput::make('value'))
                    ->default([])
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
