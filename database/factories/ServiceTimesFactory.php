<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceTimes>
 */
class ServiceTimesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $service = Service::inRandomOrder()->first();

        return [
            'service_id' => $service->id,
            'day_of_week' => rand(0, 6),
            'type' => rand(0, 1),
            'hour' => rand(0, 23),
            'minute' => rand(0, 59),
        ];
    }
}
