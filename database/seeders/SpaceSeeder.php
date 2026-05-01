<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Space;
use Illuminate\Support\Str;
class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = [
            'Cancha Central',
            'Cancha Norte',
            'Cancha Premium',
        ];

        foreach ($spaces as $name) {
            Space::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'tenis',
                'capacity' => 4,
                'description' => 'Cancha profesional de tenis.',
                'price_per_hour' => 50000,
                'is_active' => true,
            ]);
        }
    }
}