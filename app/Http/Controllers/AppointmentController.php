<?php

namespace App\Http\Controllers;

use App\Models\Service;
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

        if ($service->allow_user_selection === false || count($service->users) == 1) {
            return redirect()->action([AppointmentController::class, 'datetimepicker'], [
                'id' => $service->id,
                'userId' => $service->users()->first()->id,
                'unixTimestamp' => 0
            ]);
        }
        $availableTimes = AppointmentService::availableDatetimes($service, $unixTimestamp);

        return view('appointment.service', [
            'service' => $service,
            'availableTimes' => $availableTimes,
            'title' => $service->name,
        ]);
    }

    public function userpicker(int $id, int $unixTimestamp) {
        $service = Service::where('active', 1)->where('id', $id)->first();

        if ($service->hasOneUser()) {
            redirect();
        }

        $availableUsers = AppointmentService::availableDatetimes($service, $unixTimestamp);

        $title = $service->name . ' - ' . Carbon::createFromFormat('U', $unixTimestamp)->format('l M jS');

        return view('appointment.userpicker', [
            'service' => $service,
            'availableUsers' => $availableUsers,
            'title' => $title,
        ]);
    }

    public function datetimepicker(int $id, int $userid, int $unixTimestamp = 0) {
        $service = Service::where('active', 1)->where('id', $id)->first();
        $availableTimes = AppointmentService::availableDatetimes($service, $unixTimestamp, $userid);

        $title = $service->name . ' - ' . Carbon::createFromFormat('U', $unixTimestamp)->format('l M jS');

        $format = ($unixTimestamp) ? 'H:i' : 'd.m.Y';

        return view('appointment.datetimepicker', [
            'service' => $service,
            'availableTimes' => $availableTimes,
            'title' => $title,
            'confirm' => '', // no confirm url
            'format' => $format,
        ]);
    }

}
