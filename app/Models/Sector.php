<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    /** @use HasFactory<\Database\Factories\SectorFactory> */
    use HasFactory;

    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function availableSeats() {
        $reservedSeats = $this->tickets()
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_seats');
        return $this->seats - $reservedSeats;
    }
}
