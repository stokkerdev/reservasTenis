<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    protected $model = Availability::class;

    public function definition(): array
    {
        return [
            // No se usará directamente
        ];
    }

    public static function generateSchedules(): void
    {
        $spaces = Space::all();

        foreach ($spaces as $space) {

            // Días del 1 al 7
            for ($day = 1; $day <= 7; $day++) {

                // Bloques mañana: 6am a 12pm
                for ($hour = 6; $hour < 12; $hour++) {

                    Availability::create([
                        'space_id' => $space->id,
                        'day_of_week' => $day,
                        'start_time' => sprintf('%02d:00:00', $hour),
                        'end_time' => sprintf('%02d:00:00', $hour + 1),
                    ]);
                }

                // Bloques tarde: 2pm a 8pm
                for ($hour = 14; $hour < 20; $hour++) {

                    Availability::create([
                        'space_id' => $space->id,
                        'day_of_week' => $day,
                        'start_time' => sprintf('%02d:00:00', $hour),
                        'end_time' => sprintf('%02d:00:00', $hour + 1),
                    ]);
                }
            }
        }
    }
}