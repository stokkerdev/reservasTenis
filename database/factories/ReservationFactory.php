<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+10 days');
        $end = (clone $start)->modify('+1 hour');

        return [
            'space_id' => \App\Models\Space::inRandomOrder()->first()->id,

            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'start_time' => $start,
            'end_time' => $end,

            'status' => $this->faker->randomElement([
                'pending',
                'confirmed',
                'cancelled'
            ]),

            'user_name' => $this->faker->name,
            'user_email' => $this->faker->email,
            'notes' => $this->faker->sentence,
        ];
    }
}
