<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockedSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'start_time',
        'end_time',
        'reason'
    ];
    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}
