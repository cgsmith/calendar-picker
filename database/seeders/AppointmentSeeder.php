<?php

namespace Database\Seeders;

use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Service times
         *
         * Service avail: Monday 12 - 16
         *                Wednesday 12 - 16
         *
         * Booked Appointments: YYYY-MM- first monday at 13 - 14 and YYYY-MM- first Weds at 13 - 14
         */
        $mondayStart = Carbon::parse('next monday at 13:00');
        $mondayEnd = Carbon::parse('next monday at 14:00');
        $wednesdayStart = Carbon::parse('next wednesday at 13:00');
        $wednesdayEnd = Carbon::parse('next wednesday at 14:00');
        DB::table('appointments')->insert([
            'description' => 'Monday @ 13 booking',
            'start' => $mondayStart->format('Y-m-d H:i:s'),
            'end' => $mondayEnd->format('Y-m-d H:i:s'),
            'status' => Status::Upcoming,
            'contact_id' => 1,
            'user_id' => 1,
            'service_id' => 1,
        ]);

        DB::table('appointments')->insert([
            'description' => 'Wednesday @ 13 booking',
            'start' => $wednesdayStart->format('Y-m-d H:i:s'),
            'end' => $wednesdayEnd->format('Y-m-d H:i:s'),
            'status' => Status::Upcoming,
            'contact_id' => 1,
            'user_id' => 1,
            'service_id' => 1,
        ]);
    }
}
