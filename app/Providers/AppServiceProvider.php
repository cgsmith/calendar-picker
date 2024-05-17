<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Perform all translations
        Field::configureUsing(function (Field $field) {
            $field->translateLabel();
        });

        Column::configureUsing(function (Column $column) {
            $column->translateLabel();
        });

        Model::unguard();
    }
}
