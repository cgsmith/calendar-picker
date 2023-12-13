<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AppointmentCreated;

class SendAppointmentNotification
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
        // send email
        // save in queue for ERP to pickup later
    }
}
