<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Service;
use App\Models\ServiceTimes;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Symfony\Component\ErrorHandler\Debug;

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
        $serviceDaytimes = $service->times()->get();
        Debugbar::debug($serviceDaytimes);
        // look ahead days
        foreach ($users as $user) {
            // calculate out availability days

            //

            // tests
            if ($date) {
                for ($i = 1; $i < 11; $i++) {
                    $availability = new Availability();
                    $availability->user = $user;
                    $availability->date = Carbon::create(2023, 12, 1, $i, rand(0,59));
                    $availabilities[$user->id][] = $availability;
                }
            } else {
                $maxPerDay = $user->maximum_appointments_per_day;
                $maxLookaheadDate = 60;
                for ($i = 0; $i <= $maxLookaheadDate; $i++) {
                    $startDate = Carbon::now();
                    $startDate->add('day', $i);
                    $appointmentCount = Appointment::query()
                        ->whereRaw('DATE(start) = "' . $startDate->format('Y-m-d') . '"')
                        ->where('user_id', $user->id)
                        ->count();

                    // does date fall within hours in service times?

                    // if the appt count is less than maximum per day
                    if ($appointmentCount < $maxPerDay) {

                        $serviceCount = ServiceTimes::query()
                            ->where('service_id', $service->id)
                            ->where('day_of_week', $startDate->format('w')  )
                            ->count();
                        if ($serviceCount > 0) {
                            $availability = new Availability();
                            $availability->user = $user;
                            $availability->date = Carbon::createFromFormat('Y-m-d',$startDate->format('Y-m-d'));
                            $availabilities[$user->id][] = $availability;
                        }
                    }
                }
            }
        }

        return $availabilities;
    }

    protected function getAvailableDays()
    {
        $sql = "SET @max_appointments_per_day = 1; -- Replace this with the user's maximum appointments per day
SET @days_in_advance = 7; -- Replace this with the user's configurable days in advance
SET @day_max_limit = 60; -- the maximum amount of days to return
SET @user_ids = 3; -- Comma-separated list of user IDs


SELECT possible_dates.possible_date
FROM (
         SELECT DATE(CURDATE() + INTERVAL seq.seq DAY) AS possible_date
         FROM (
                  SELECT a.N + b.N * 10 + c.N * 100 AS seq
                  FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS a
                           CROSS JOIN (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS b
                           CROSS JOIN (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS c
              ) AS seq
         WHERE DATE(CURDATE() + INTERVAL seq.seq DAY) BETWEEN CURDATE() AND CURDATE() + INTERVAL @day_max_limit day
     ) AS possible_dates
         LEFT JOIN (
    SELECT DATE(start) AS appointment_date, COUNT(*) AS appointments_count
    FROM appointments
    WHERE user_id = @user_ids -- Filter appointments by multiple user_ids
    GROUP BY appointment_date
) AS existing_appointments ON possible_dates.possible_date = existing_appointments.appointment_date
WHERE COALESCE(existing_appointments.appointments_count, 0) < @max_appointments_per_day OR existing_appointments.appointments_count IS NULL;";
    }

}
