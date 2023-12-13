<?php

namespace Database\Seeders;

use App\Enums\DayOfWeek;
use App\Enums\ServiceTimeType;
use App\Models\Service;
use App\Models\ServiceQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one service and another with user selection
        DB::table('services')->insert([
            'name' => 'Workshop Appointment',
            'description' => '<strong>We will repair all bikes!</strong><p>Except fixies</p>',
            'duration' => 1,
            'all_day' => 1,
            'minimum_cancel_hours' => 2,
            'allow_user_selection' => 0,
            'active' => 1,
        ]);

        // 2
        DB::table('services')->insert([
            'name' => 'Workshop Appointment with user selection',
            'description' => 'This is a description',
            'duration' => 1,
            'all_day' => 1,
            'minimum_cancel_hours' => 2,
            'allow_user_selection' => 1,
            'active' => 1,
        ]);

        DB::table('services')->insert([
            'name' => 'Workshop Appointment with multiple users but no selection',
            'description' => 'This is a description',
            'duration' => 1,
            'all_day' => 1,
            'minimum_cancel_hours' => 2,
            'allow_user_selection' => 0,
            'active' => 1,
        ]);

        // Attach 1 user to service
        Service::find(1)->users()->attach(1);
        Service::find(2)->users()->attach(1);
        Service::find(3)->users()->attach(1);
        Service::find(3)->users()->attach(2);

        /**
         * Service times
         *
         * Service avail: Monday 12 - 16
         *                Wednesday 12 - 16
         */
        DB::table('service_times')->insert([
            'service_id' => 1,
            'day_of_week' => DayOfWeek::Monday,
            'type' => ServiceTimeType::Start,
            'hour' => 12,
            'minute' => 0,
        ]);
        DB::table('service_times')->insert([
            'service_id' => 1,
            'day_of_week' => DayOfWeek::Monday,
            'type' => ServiceTimeType::End,
            'hour' => 16,
            'minute' => 0,
        ]);
        DB::table('service_times')->insert([
            'service_id' => 1,
            'day_of_week' => DayOfWeek::Wednesday,
            'type' => ServiceTimeType::Start,
            'hour' => 12,
            'minute' => 0,
        ]);
        DB::table('service_times')->insert([
            'service_id' => 1,
            'day_of_week' => DayOfWeek::Wednesday,
            'type' => ServiceTimeType::End,
            'hour' => 16,
            'minute' => 0,
        ]);

        // Create full week of availability for service_id 2 - daily from 12 - 18:30
        for ($i = 0; $i < 7; $i++) {
            DB::table('service_times')->insert([
                'service_id' => 2,
                'day_of_week' => $i,
                'type' => ServiceTimeType::Start,
                'hour' => 12,
                'minute' => 0,
            ]);
            DB::table('service_times')->insert([
                'service_id' => 2,
                'day_of_week' => $i,
                'type' => ServiceTimeType::End,
                'hour' => 18,
                'minute' => 30,
            ]);
        }

        ServiceQuestion::factory()
            ->count(5)
            ->create();
    }
}
