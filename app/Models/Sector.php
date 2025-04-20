<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    /** @use HasFactory<\Database\Factories\SectorFactory> */
    use HasFactory;

    protected $guarded = [];

    public function sector() {
        return $this->belongsTo(Sector::class);
    }

    public function event() {
        return $this->belongsToMany(Event::class, 'sector_prices')->withPivot('price');
    }

    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    /*public function availableSeats() {
        $reservedSeats = $this->tickets()
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_seats');
        return $this->seats - $reservedSeats;
    }*/
}
