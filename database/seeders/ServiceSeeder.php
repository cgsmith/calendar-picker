<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceQuestion;
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
        ServiceQuestion::factory()
            ->count(5)
            ->create();
    }
}
