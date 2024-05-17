<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $settings = GeneralSetting::class;

    protected static ?int $navigationSort = 6;

    public function getTitle(): string|Htmlable
    {
        return __(parent::getTitle());
    }

    public static function getNavigationLabel(): string
    {
        return __('Manage General');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('minimum_day_lookahead')->integer()->required(),
                Forms\Components\TextInput::make('maximum_day_lookahead')->integer()->required(),
            ]);
    }
}
