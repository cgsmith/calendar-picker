<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceQuestion>
 */
class ServiceQuestionFactory extends Factory
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
            'question' => fake()->text(),
            'type' => fake()->randomElement(['text','textarea','option','checkbox']),
            'type_meta' => '[{value: "element-1", description: "This is the first element"}, {value: "element-2", description: "This is the first element"}]',
            'required' => fake()->boolean()
        ];
    }
}
