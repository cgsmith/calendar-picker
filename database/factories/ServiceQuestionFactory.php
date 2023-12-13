<?php

namespace Database\Factories;

use App\Enums\QuestionType;
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
            'hint' => fake()->text(),
            'type' => fake()->randomElement(QuestionType::cases()),
            'type_meta' => [fake()->text(10), fake()->text(5), fake()->text(15)],
            'order' => rand(0, 100),
            'required' => fake()->boolean(),
        ];
    }
}
