<?php

namespace Database\Seeders;

use App\Enums\DayOfWeek;
use App\Enums\ServiceTimeType;
use App\Enums\Status;
use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\ServiceTimes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one service
        DB::table('services')->insert([
            'name' => 'Workshop Appointment',
            'description' => '<strong>We will repair all bikes!</strong><p>Except fixies</p>',
            'duration' => 1,
            'all_day' => 1,
            'minimum_cancel_hours' => 2,
            'allow_user_selection' => 0,
            'active' => 1
        ]);

        // Attach 1 user to service
        Service::find(1)->users()->attach(1);

        // Add service times
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

        // seed users with belongsToMany relation
        foreach (Service::all() as $service) {
            $service->users()->attach(rand(2, 5)); // don't seed user 1 for unit tests
        }

        ServiceQuestion::factory()
            ->count(5)
            ->create();
        ServiceTimes::factory()
            ->count(5)
            ->create();
    }
}
