<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Http\Middleware\Localization;
use App\Livewire\UserSettingsComponent;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->plugin(
                BreezyCore::make()->myProfile()
                    ->myProfileComponents([
                        UserSettingsComponent::class,
                    ])
                    ->enableSanctumTokens()
            )
            ->colors([
                'primary' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([Widgets\AccountWidget::class])
            ->navigationItems([
                NavigationItem::make(__('Booking Page'))
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->sort(10),
                NavigationItem::make(__('Help'))
                    ->url('https://wiki.mount7.com/books/a-allgemein/page/werkstatt-buchungs-tool', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-question-mark-circle')
                    ->sort(10),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->url(fn (): string => route('filament.admin.auth.profile'))
                    ->label(__('Language'))
                    ->icon('heroicon-o-language'),
            ])
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(asset('images/logo.svg'))
            ->darkModeBrandLogo(asset('images/logo-dark.svg'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                Localization::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
