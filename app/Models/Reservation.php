<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BlockedSlot;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'user_id',
        'start_time',
        'end_time',
        'status',
        'user_name',
        'user_email',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check for conflicts with existing reservations or blocked slots.
     *
     * @param int $spaceId
     * @param string $startTime
     * @param string $endTime
     * @param int|null $reservationId Exclude this reservation ID from conflict check (for updates).
     * @return bool True if a conflict exists, false otherwise.
     */
    public static function hasConflict(int $spaceId, string $startTime, string $endTime, ?int $reservationId = null, ?int $blockedSlotId = null): bool
    {
        // Check for overlapping reservations
        $conflictingReservations = self::where('space_id', $spaceId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            });

        if ($reservationId) {
            $conflictingReservations->where('id', '!=', $reservationId);
        }

        if ($conflictingReservations->exists()) {
            return true;
        }

        // Check for overlapping blocked slots
        $conflictingBlockedSlots = BlockedSlot::where('space_id', $spaceId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            });

        if ($blockedSlotId) {
            $conflictingBlockedSlots->where('id', '!=', $blockedSlotId);
        }

        return $conflictingBlockedSlots->exists();
    }
}
