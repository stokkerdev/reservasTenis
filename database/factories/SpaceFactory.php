<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Space>
 */
class SpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Cancha ' . $this->faker->unique()->numberBetween(1, 999);

       return [
    'name' => $name,
    'slug' => \Illuminate\Support\Str::slug($name),
    'type' => 'tenis',
    'capacity' => $this->faker->numberBetween(2, 4),
    'description' => $this->faker->sentence,
    'price_per_hour' => $this->faker->randomFloat(2, 20000, 80000),
    'is_active' => true
];
    }
}
