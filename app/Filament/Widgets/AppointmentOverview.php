<?php
declare(strict_types=1);
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
            Stat::make(__('Total Appointments Today'),
                Appointment::query()
                    ->whereDate('start', Carbon::today())
                    ->count()
                )
                ->icon('heroicon-o-user-group'),
            Stat::make(__('Your Appointments Today'),
                Appointment::query()
                    ->whereDate('start', Carbon::today())
                    ->where('user_id', auth()->user()->id)
                    ->count()
                )
                ->icon('heroicon-o-user'),
        ];
    }
}
