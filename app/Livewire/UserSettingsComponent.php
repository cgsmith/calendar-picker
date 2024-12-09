<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Locale;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

/**
 * @property Form $form
 */
class UserSettingsComponent extends MyProfileComponent
{
    protected string $view = 'livewire.user-settings-component';

    /**
     * @var array<string, string> key value pair of settings
     */
    public array $data;

    /** @var array<string> */
    public array $only = ['maximum_appointments_per_day', 'locale'];

    public User $user;

    public string|false $userClass;

    /** @var int */
    public static $sort = 11;

    /**
     * @return void
     */
    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('maximum_appointments_per_day')
                    ->label(__('Maximum Appointments Per Day')),
                Select::make('locale')
                    ->label(__('Language'))
                    ->prefixIcon('heroicon-o-language')
                    ->options(Locale::getOptions())
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title(__('User settings updated successfully'))
            ->send();

        // redirect to Locale so we can set it in the middleware
        $this->redirectRoute('filament.admin.pages.my-profile', ['lang' => $this->user->locale]);
    }
}
