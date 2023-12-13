<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function getModelLabel(): string
    {
        return __('Appointment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Appointments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contact_id')
                    ->relationship('contact', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone number')
                            ->tel(),
                    ])
                    ->translateLabel(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone number')
                            ->tel(),
                    ])
                    ->translateLabel(),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->columnSpanFull()
                    ->preload()
                    ->required()
                    ->translateLabel(),
                Forms\Components\RichEditor::make('description')
                    ->toolbarButtons([
                        'bold', 'italic', 'redo', 'undo',
                    ])
                    ->columnSpanFull()
                    ->required()
                    ->translateLabel(),
                Forms\Components\DateTimePicker::make('start')
                    ->seconds(false)
                    ->native(false)
                    ->required()
                    ->translateLabel(),
                Forms\Components\DateTimePicker::make('end')
                    ->seconds(false)
                    ->native(false)
                    ->after('start')
                    ->required()
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('end')
                    ->dateTime()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'past' => __('Past'),
                        'today' => __('Today'),
                        'upcoming' => __('Upcoming'),
                    ])
                    ->translateLabel(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
