<?php

namespace App\Filament\Pages;


class Dashboard extends \Filament\Pages\Dashboard
{

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

}
