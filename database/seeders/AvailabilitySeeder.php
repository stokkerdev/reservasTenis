<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Availability;
use App\Models\Space;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $spaces = Space::all();

        foreach ($spaces as $space) {
            // Para cada día de la semana (0 = Domingo, 6 = Sábado)
            for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
                // Bloques de la mañana: 7 AM a 12 PM
                for ($hour = 7; $hour < 12; $hour++) {
                    Availability::create([
                        'space_id' => $space->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => Carbon::createFromTime($hour, 0, 0)->format('H:i:s'),
                        'end_time' => Carbon::createFromTime($hour + 1, 0, 0)->format('H:i:s'),
                    ]);
                }

                // Bloques de la tarde: 2 PM a 8 PM
                for ($hour = 14; $hour < 20; $hour++) {
                    Availability::create([
                        'space_id' => $space->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => Carbon::createFromTime($hour, 0, 0)->format('H:i:s'),
                        'end_time' => Carbon::createFromTime($hour + 1, 0, 0)->format('H:i:s'),
                    ]);
                }
            }
        }
    }
}
