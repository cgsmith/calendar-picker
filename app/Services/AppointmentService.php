<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AppointmentMeta;
use App\Models\Availability;
use App\Models\Holiday;
use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\User;
use App\Settings\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentService
{
    public static function availableDatetimes(
        Service $service,
        int $userid = 0,
        ?int $date = null,
        ?int $startDate = null,
    ): array {
        /**
         * 1. If $date is null then we first need to fetch the dates available - not times
         */
        $availabilities = [];

        // get specific user or get all users
        $users = ($userid) ? User::where('id', $userid)->get() : $service->users()->get();
        $maxApptPerDay = $users->sum('maximum_appointments_per_day');

        if (! $startDate) {
            $startDate = Carbon::now(config('app.timezone'));
        } else {
            $startDate = Carbon::createFromTimestamp($startDate, config('app.timezone'));
        }

        if ($date) {
        } else {
            // set the startdate ahead the appropriate minimum lookahead
            $startDate->add('day', app(GeneralSetting::class)->minimum_day_lookahead);
            for ($i = 0;
                $i <= app(GeneralSetting::class)->maximum_day_lookahead;
                $i++) {
                // check if there is a holiday
                if (Holiday::where('date', $startDate->format('Y-m-d'))->exists()) {
                    // skip date
                    continue;
                }

                $appointmentCount = $service
                    ->appointment()
                    ->whereRaw('DATE(start) = "'.$startDate->format('Y-m-d').'"')
                    ->whereIn('user_id', $users->modelKeys())
                    ->count();

                // does date fall within hours in service times?

                // if the appt count is less than maximum per day
                if ($appointmentCount < $maxApptPerDay) {
                    $serviceCount = $service
                        ->times()
                        ->where('day_of_week', $startDate->format('w'))
                        ->count();
                    if ($serviceCount > 0) {
                        $availableDate = Carbon::createFromFormat('Y-m-d', $startDate->format('Y-m-d'));
                        $availableDate->setTime(0, 0, 0);
                        $availability = new Availability();
                        $availability->user = $userid;
                        $availability->date = $availableDate;
                        $availabilities[] = $availability;
                    }
                }
                $startDate->add('day', 1);
            }
        }

        return $availabilities;
    }

    public static function buildMetaArray(Request $request): array
    {
        $metaArray = [];
        if (! $request->has('questions')) {
            return [];
        }

        foreach ($request->questions as $questionKey => $questionMeta) {
            $question = ServiceQuestion::find($questionKey);
            $answer = (! is_null($questionMeta)) ? strip_tags($questionMeta) : 'null';

            $meta = new AppointmentMeta();
            $meta->key = $question->key;
            $meta->value = $answer;
            $metaArray[] = $meta;
        }

        return $metaArray;
    }
}
