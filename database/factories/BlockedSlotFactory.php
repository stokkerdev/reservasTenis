<?php

namespace Database\Factories;

use App\Models\BlockedSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlockedSlot>
 */
class BlockedSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $start = fake()->dateTimeBetween('+1 day', '+15 days');
    $end = (clone $start)->modify('+2 hours');

    return [
        'space_id' => \App\Models\Space::inRandomOrder()->first()->id,

        'start_time' => $start,
        'end_time' => $end,

        'reason' => fake()->randomElement([
            'Mantenimiento',
            'Limpieza',
            'Evento interno',
            'Inspección'
        ])
    ];
}
}
