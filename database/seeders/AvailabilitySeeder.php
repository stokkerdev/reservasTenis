<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Availability;
class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        Availability::factory()->count(6)->create();
    }
}