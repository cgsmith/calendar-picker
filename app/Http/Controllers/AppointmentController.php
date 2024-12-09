<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ServiceTimeType;
use App\Enums\Status;
use App\Events\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Contact;
use App\Models\Service;
use App\Models\ServiceTimes;
use App\Models\User;
use App\Services\AppointmentService;
use App\Settings\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Index of the appointment controller
     */
    public function index(): View
    {
        return view('appointment.index', [
            'services' => Service::where('active', 1)->get(),
            'site_notice' => app(GeneralSetting::class)->site_notice,
        ]);
    }

    /**
     * After a service is selected we show the appointments available for selection
     */
    public function service(int $id, int $unixTimestamp = 0): View|RedirectResponse
    {
        $service = Service::where('active', 1)->where('id', $id)->first();

        $userCount = $service->users()->count() === 1;
        if (! $service->allow_user_selection || $userCount) {
            /** @phpstan-ignore property.notFound */
            $userid = ($userCount) ? $service->users()->first()->id : 0;

            return redirect()->action([AppointmentController::class, 'datetimepicker'], [
                'id' => $service->id,
                'userId' => $userid,
                'unixTimestamp' => 0,
            ]);
        }
        $availableTimes = AppointmentService::availableDatetimes($service, $unixTimestamp);

        return view('appointment.service', [
            'service' => $service,
            'availableTimes' => $availableTimes,
            'title' => $service->name,
        ]);
    }

    /**
     * If there is a user to be picked then we should display that to the customer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function userpicker(int $id, int $unixTimestamp)
    {
        $service = Service::where('active', 1)->where('id', $id)->first();

        $availableUsers = AppointmentService::availableDatetimes($service, $unixTimestamp);

        $title = $service->name.' - '.Carbon::createFromTimestamp($unixTimestamp, config('app.timezone'))->format('l M jS');

        return view('appointment.userpicker', [
            'service' => $service,
            'availableUsers' => $availableUsers,
            'title' => $title,
        ]);
    }

    /**
     * Render date time picker for user to choose a selection
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function datetimepicker(int $id, int $userid, int $unixTimestamp = 0)
    {
        $service = Service::where('active', 1)->where('id', $id)->first();
        $availableTimes = AppointmentService::availableDatetimes(
            service: $service,
            userid: $userid,
            date: $unixTimestamp,
        );

        $title = $service->name.' - '.Carbon::createFromTimestamp($unixTimestamp, config('app.timezone'))->format('l M jS');

        $format = ($unixTimestamp) ? 'H:i' : 'd.m.Y';

        return view('appointment.datetimepicker', [
            'service' => $service,
            /** @phpstan-ignore property.notFound */
            'userid' => $service->users()->first()->id,
            'availableTimes' => $availableTimes,
            'title' => $title,
            'confirm' => '', // no confirm url
            'format' => $format,
        ]);
    }

    /**
     * Confirm the appointment and save to the datastore
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function confirm(int $id, int $userid, int $unixTimestamp)
    {
        /** @var Service $service */
        $service = Service::where('active', 1)->where('id', $id)->first();
        $date = Carbon::createFromTimestamp($unixTimestamp, config('app.timezone'));
        $start = clone $date;
        $end = clone $date;
        if ($service->all_day) {

            /** @var ServiceTimes|null $dayOfWeekStartTime */
            $dayOfWeekStartTime = $service
                ->times()
                ->where('day_of_week', '=', $date->format('w'))
                ->where('type', '=', ServiceTimeType::Start)
                ->first();

            /** @var ServiceTimes|null $dayOfWeekEndTime */
            $dayOfWeekEndTime = $service
                ->times()
                ->where('day_of_week', '=', $date->format('w'))
                ->where('type', '=', ServiceTimeType::End)
                ->first();

            // If dayOfWeek not found (for some reason) just use the current time that was passed through
            if (is_null($dayOfWeekStartTime) || is_null($dayOfWeekEndTime)) {
                $start = $end = Carbon::createFromTimestamp($unixTimestamp, config('app.timezone'));
            } else {
                $start = clone $date->setHour($dayOfWeekStartTime->hour)->setMinute($dayOfWeekStartTime->minute);
                $end = clone $date->setHour($dayOfWeekEndTime->hour)->setMinute($dayOfWeekEndTime->minute);
            }
        }

        // Create draft appointment - this will lock in the appointmnet - we can delete drafts after a certain amount of time
        return view('appointment.confirm', [
            'service' => $service,
            'title' => $service->name.': '.__("let's gather a little more information"),
            'userid' => $userid,
            'start' => $start->format('U'),
            'end' => $end->format('U'),
            'questions' => $service->questions,
        ]);
    }

    /**
     * Confirmation post of the form
     *
     * @return RedirectResponse|void
     */
    public function confirmPost(\Illuminate\Http\Request $request)
    {
        if ($request->isMethod('post')) {
            // get form data
            $contact = Contact::updateOrCreate(
                ['email' => $request->email],
                ['name' => $request->name, 'phone' => $request->phone]
            );

            // validate service_id
            $service = Service::find($request->service_id);
            $user = User::find($request->user_id);

            $start = Carbon::createFromFormat('U', $request->start);
            $end = Carbon::createFromFormat('U', $request->end);

            $metaArray = AppointmentService::buildMetaArray($request);

            $appointment = Appointment::create([
                'contact_id' => $contact->id,
                /** @phpstan-ignore property.notFound */
                'service_id' => $service->id,
                /** @phpstan-ignore property.notFound */
                'user_id' => $user->id,
                'status' => Status::Upcoming,
                'start' => $start,
                'end' => $end,
            ]);

            $appointment->meta()->saveMany($metaArray);

            AppointmentCreated::dispatch($appointment);

            return to_route('appt.thankyou');
        }
    }

    /**
     * Thank you page 🎉
     */
    public function thankYou(): View
    {
        return view('appointment.thankyou');
    }
}
