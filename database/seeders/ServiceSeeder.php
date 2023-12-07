<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\ServiceTimes;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::factory()
            ->count(5)
            ->create();

        // seed users with belongsToMany relation
        foreach (Service::all() as $service) {
            $service->users()->attach(rand(1,5));
        }

        ServiceQuestion::factory()
            ->count(5)
            ->create();
        ServiceTimes::factory()
            ->count(5)
            ->create();
    }
}
