<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class AppointmentService
{

    /**
     * - activeServices
     * - availableAppointments
     */
    public static function availableTimes(Carbon $date, Service $service)
    {
        // get all appointments for selected date and service
        $appointments = Appointment::query()->where('');

        // get all employees available for service

        return ['11:30', '12:30', '14:30'];
    }

    public static function availableDatetimes(Service $service, int|null $date, int $userid = 0): array
    {
        /**
         * 1. If $date is null then we first need to fetch the dates available - not times
         *
         */
        // get all appointments for date range on service

        // get all employees for available service

        $availabilities = [];

        // get specific user or get all users
        $users = ($userid) ? User::where('id', $userid)->get() : $service->users()->get();

        foreach ($users as $user) {
            // calculate out availability days

            // tests
            if ($date) {
                for ($i = 1; $i < 11; $i++) {
                    $availability = new Availability();
                    $availability->user = $user;
                    $availability->date = Carbon::create(2023, 12, 1, $i, rand(0,59));
                    $availabilities[$user->id][] = $availability;
                }
            } else {
                for ($i = 1; $i < 11; $i++) {
                    $availability = new Availability();
                    $availability->user = $user;
                    $availability->date = Carbon::create(2023, 12, $i, 12);
                    $availabilities[$user->id][] = $availability;
                }
            }

        }




        return $availabilities;
    }

}
