<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'datetime', // Automatyczna konwersja na obiekt Carbon
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function sectors() {
        return $this->belongsToMany(Sector::class, 'sector_prices')->withPivot('price');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function organizer() {
        return $this->belongsTo(Organizer::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function occupancy()
    {
        $sectors = $this->sectors()->get();

        $totalSeats = 0;
        $occupiedSeats = 0;

        foreach ($sectors as $sector) {
            $seats = $sector->getAllSeats($this->id);
            $totalSeats += $seats->count();
            $occupiedSeats += $seats->where('available', false)->count();
        }

        return $totalSeats > 0 ? round(($occupiedSeats / $totalSeats) * 100, 2) : 0;
    }
}
