<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Models\Appointment;
use Illuminate\Support\Facades\Queue;

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
        $appointment = Appointment::with(['service', 'user', 'contact', 'meta'])->where('id', $event->appointment->id)->get();
        Queue::pushRaw((string) $appointment);
    }
}
