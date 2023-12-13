<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->streetName(),
            'description' => fake()->text(),
            'duration' => fake()->randomElement([15, 30, 90, 120]),
            'minimum_cancel_hours' => fake()->randomElement([null, 2, 4, 8, 24]),
            'all_day' => fake()->boolean(80),
            'allow_user_selection' => fake()->boolean(),
            'active' => fake()->boolean(80),
        ];
    }
}
