<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\PushAppointment;
use App\Models\Appointment;

class CreateAppointmentJob
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCreated $event): void
    {
        $appointment = Appointment::with(['service', 'user', 'contact'])->where('id', $event->appointment->id)->get();
        \Illuminate\Support\Facades\Queue::pushRaw((string) $appointment);
        //PushAppointment::dispatch($event->appointment);
    }
}
