<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // start with blank description
        $description = '';

        /** @var Service $service */
        $service = Service::find(1);

        /** @var ServiceQuestion $question */
        foreach ($service->questions as $question) {
            $description .= '<strong>' . $question->question . '</strong><br/>';
            $description .= fake()->text . '<br/><br/><br/>';
        }

        return [
            'description' => $description,
            'start' => '2023',
            'end' => fake()->dateTime()->setDate(date('Y'), date('m'), rand(1, 28)),
            'contact_id' => Contact::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'service_id' => 1,
            'status' => fake()->randomElement(['today', 'upcoming', 'past']),
        ];
    }
}
