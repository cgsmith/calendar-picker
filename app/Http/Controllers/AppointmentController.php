<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ServiceTimeType;
use App\Enums\Status;
use App\Models\Appointment;
use App\Models\Contact;
use App\Models\Service;
use App\Models\ServiceTimes;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        return view('appointment.index', [
            'services' => Service::where('active', 1)->get(),
        ]);
    }

    public function service(int $id, int $unixTimestamp = 0): View|RedirectResponse
    {
        $service = Service::where('active', 1)->where('id', $id)->first();

        $userCount = $service->users()->count() === 1;
        if (!$service->allow_user_selection || $userCount) {
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

    public function userpicker(int $id, int $unixTimestamp)
    {
        $service = Service::where('active', 1)->where('id', $id)->first();

        $availableUsers = AppointmentService::availableDatetimes($service, $unixTimestamp);

        $title = $service->name . ' - ' . Carbon::createFromTimestamp($unixTimestamp)->format('l M jS');

        return view('appointment.userpicker', [
            'service' => $service,
            'availableUsers' => $availableUsers,
            'title' => $title,
        ]);
    }

    public function datetimepicker(int $id, int $userid, int $unixTimestamp = 0)
    {
        $service = Service::where('active', 1)->where('id', $id)->first();
        $availableTimes = AppointmentService::availableDatetimes(
            service: $service,
            date: $unixTimestamp,
            userid: $userid,
        );

        $title = $service->name . ' - ' . Carbon::createFromTimestamp($unixTimestamp)->format('l M jS');

        $format = ($unixTimestamp) ? 'H:i' : 'd.m.Y';

        return view('appointment.datetimepicker', [
            'service' => $service,
            'userid' => $service->users()->first()->id,
            'availableTimes' => $availableTimes,
            'title' => $title,
            'confirm' => '', // no confirm url
            'format' => $format,
        ]);
    }

    public function confirm(int $id, int $userid, int $unixTimestamp)
    {
        /** @var Service $service */
        $service = Service::where('active', 1)->where('id', $id)->first();
        $date = Carbon::createFromTimestamp($unixTimestamp);
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
                $start = $end = Carbon::createFromTimestamp($unixTimestamp);
            } else {
                $start = clone $date->setHour($dayOfWeekStartTime->hour)->setMinute($dayOfWeekStartTime->minute);
                $end = clone $date->setHour($dayOfWeekEndTime->hour)->setMinute($dayOfWeekEndTime->minute);
            }
        }

        // Create draft appointment - this will lock in the appointmnet - we can delete drafts after a certain amount of time
        return view('appointment.confirm', [
            'service' => $service,
            'title' => $service->name . ': ' . __("let's gather a little more information"),
            'userid' => $userid,
            'start' => $start->format('U'),
            'end' => $end->format('U'),
            'questions' => $service->questions,
        ]);
    }

    public function thankyou(\Illuminate\Http\Request $request)
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

            // description build out HTML for saving on the appointment
            $description = AppointmentService::buildDescription($request);

            $appointment = Appointment::create([
                'contact_id' => $contact->id,
                'service_id' => $service->id,
                'user_id' => $user->id,
                'description' => $description,
                'status' => Status::Upcoming,
                'start' => $start,
                'end' => $end,
            ]);

            return view('appointment.thankyou', [
                'appointment' => $appointment,
            ]);
        }
    }
}
