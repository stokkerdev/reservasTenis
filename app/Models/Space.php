<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Space extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'type',
        'capacity',
        'description',
        'price_per_hour',
        'is_active',
        'image_path'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class);
    }

    /**
     * Generates available time blocks for a given date based on the space's availabilities.
     *
     * @param Carbon $date The date for which to generate blocks.
     * @param int $intervalMinutes The duration of each block in minutes (e.g., 60 for 1 hour).
     * @return array An array of available time blocks, each with 'start_time' and 'end_time'.
     */
    public function generateTimeBlocks(Carbon $date, int $intervalMinutes = 60): array
    {
        $dayOfWeek = $date->dayOfWeek; // 0 for Sunday, 1 for Monday, etc.
        $availableBlocks = [];

        $availabilities = $this->availabilities()->where('day_of_week', $dayOfWeek)->get();

        foreach ($availabilities as $availability) {
            $start = Carbon::parse($date->toDateString() . ' ' . $availability->start_time);
            $end = Carbon::parse($date->toDateString() . ' ' . $availability->end_time);

            while ($start->copy()->addMinutes($intervalMinutes)->lte($end)) {
                $blockStart = $start->copy();
                $blockEnd = $start->copy()->addMinutes($intervalMinutes);

                $availableBlocks[] = [
                    'start_time' => $blockStart->format('Y-m-d H:i:s'),
                    'end_time' => $blockEnd->format('Y-m-d H:i:s'),
                ];

                $start->addMinutes($intervalMinutes);
            }
        }

        return $availableBlocks;
    }
}
