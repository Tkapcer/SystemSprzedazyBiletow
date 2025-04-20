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

    public function organizer() {
        return $this->belongsTo(Organizer::class);
    }
}
