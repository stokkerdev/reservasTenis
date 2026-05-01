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
        $name = 'Cancha ' . fake()->unique()->numberBetween(1, 999);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'type' => 'tenis',
            'capacity' => fake()->numberBetween(2, 4),
            'description' => fake()->sentence(),
            'price_per_hour' => fake()->randomFloat(2, 20000, 80000),
            'is_active' => true
        ];
    }
}
