<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $description;

    public string $service_name;

    public string $date;

    public string $contact_name;

    public string $contact_email;

    public string $contact_phone;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $appointment)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
