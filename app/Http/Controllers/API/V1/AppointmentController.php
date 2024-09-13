<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;
use App\Models\Contact;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // validate data
            $this->validate($request, [
                '*.external_id' => 'required|max:255',
                '*.start' => 'required|date|date_format:Y-m-d',
            ]);

            $json = json_decode($request->getContent());
            $json = (is_array($json)) ? $json : [$json];

            foreach ($json as $item) {
                $this->createAppointment($item);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Appointments not created', 'error' => $e->getMessage()], 500);
        }
        DB::commit();

        return response()->json(['message' => 'Appointments created', 'data' => count($json)], 201);
    }

    private function createAppointment($item)
    {
        $date = Carbon::parse($item->start);

        /**
         * Try to find the external_id first to prevent duplicates
         */
        if (! $appointment = Appointment::where('external_id', $item->external_id)->first()) {
            $appointment = new Appointment;

            // Fake Contact find or create
            if (! $contact = Contact::where('email', 'no@email.com')->first()) {
                $contact = new Contact;
                $contact->name = 'From External';
                $contact->email = 'no@email.com';
                $contact->phone = '123';
                $contact->save();
            }

            $user = User::find(1);
            $service = Service::find(1);

            $appointment->external_id = $item->external_id;
            $appointment->description = 'Description from external source';
            $appointment->status = 'upcoming';
            $appointment->contact()->associate($contact);
            $appointment->user()->associate($user);
            $appointment->service()->associate($service);
        }

        $appointment->start = $date->startOfDay()->format('Y-m-d H:i:s');
        $appointment->end = $date->endOfDay()->format('Y-m-d H:i:s');

        $appointment->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update([
            'external_id' => $request->get('external_id'),
        ]);
    }
}
