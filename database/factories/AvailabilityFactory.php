<?php

namespace Database\Factories;

use App\Models\Availability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'space_id' => \App\Models\Space::inRandomOrder()->first()->id,

            'day_of_week' => fake()->numberBetween(1, 7),

            'start_time' => '08:00:00',
            'end_time' => '22:00:00',
        ];
    }
}
