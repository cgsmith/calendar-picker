<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\Locale;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    protected function getRedirectUrl(): ?string
    {
        return '/admin/profile?lang='.$this->data['locale'];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getLanguageFormComponent(),
                TextInput::make('maximum_appointments_per_day'),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    public static function getLanguageFormComponent(): Component
    {
        return Select::make('locale')
            ->label(__('Language'))
            ->prefixIcon('heroicon-o-language')
            ->options(Locale::getOptions())
            ->required();
    }
}
