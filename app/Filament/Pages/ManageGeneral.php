<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSetting::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('minimum_day_lookahead')
                    ->integer()
                    ->required(),
                Forms\Components\TextInput::make('maximum_day_lookahead')
                    ->integer()
                    ->required(),
            ]);
    }
}
