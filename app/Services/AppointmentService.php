<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\ServiceTimes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $maxApptPerDay = $users->sum('maximum_appointments_per_day');
        // look ahead days
            // calculate out availability days

            //

            // tests
            if ($date) {
            } else {
                $minimumLookaheadDate = 0; // how far to start looking ahead
                $maxLookaheadDate = 60;
                for ($i = $minimumLookaheadDate; $i <= ($maxLookaheadDate + $minimumLookaheadDate); $i++) {
                    $startDate = Carbon::now();
                    $startDate->add('day', $i);
                    $appointmentCount = Appointment::query()
                        ->whereRaw('DATE(start) = "' . $startDate->format('Y-m-d') . '"')
                        ->whereIn('user_id', $users->modelKeys())
                        ->count();

                    // does date fall within hours in service times?

                    // if the appt count is less than maximum per day
                    if ($appointmentCount < $maxApptPerDay) {
                        $serviceCount = ServiceTimes::query()
                            ->where('service_id', $service->id)
                            ->where('day_of_week', $startDate->format('w')  )
                            ->count();
                        if ($serviceCount > 0) {
                            $availability = new Availability();
                            $availability->user = $userid;
                            $availability->date = Carbon::createFromFormat('Y-m-d',$startDate->format('Y-m-d'));
                            $availabilities[] = $availability;
                        }
                    }
                }
            }


        return $availabilities;
    }

    public static function buildDescription(Request $request)
    {
        $description = '<ul>';
        foreach ($request->questions as $questionKey => $questionMeta) {
            $question = ServiceQuestion::find($questionKey);
            $meta = strip_tags($questionMeta);
            //var_dump($questionKey);die;
            $description .= "<li>{$question->question}</li><ul><li>{$meta}</li></ul>";
        }
        return $description . '</ul>';
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
