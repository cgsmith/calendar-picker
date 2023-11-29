<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AppointmentOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Appointments Today',
                Appointment::query()
                    ->whereDate('start', Carbon::today())
                    ->count()
            )
        ];
    }
}
