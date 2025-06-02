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

    public function getPriceForSeat($event_id) {
        return $this->event()->where('event_id', $event_id)->first()->pivot->price ?? 0;
    }

    public function getAllSeats($event_id): \Illuminate\Support\Collection
    {
        $seats = collect();

//        $price = $this->event()->where('event_id', $event_id)->first()->pivot->price;
        $price = $this->getPriceForSeat($event_id);

        for ($row = 1; $row <= $this->rows; $row++) {
            for ($column = 1; $column <= $this->columns; $column++) {
                $seats->push(new Seat($event_id, $this->id, $row, $column, $price));
            }
        }

        return $seats;
    }

    /*public function getAllSeats($event_id) {
        $allSeats = $this->generateSeats($event_id);

        $takenSeats = Ticket::where('sector_id', $this->id)
            ->where('event_id', $event_id)
            ->whereIn('status', ['purchased', 'reserved'])
            ->get();

        foreach ($allSeats as $seat) {
            foreach ($takenSeats as $takenSeat) {
                if ($seat->row === $takenSeat->row && $seat->column === $takenSeat->column) {
                    $seat->available = false;
                    break;
                }
            }
        }

        return $allSeats;
    }*/

    /*public function allSeats() {
        $allSeats = collect();

        for ($row = 1; $row <= $this->rows; $row++) {
            for ($column = 1; $column <= $this->collumn; $column++) {
                $allSeats->push($row . $column);
            }
        }

        return $allSeats;
    }*/

    /*public function availableSeats() {
        $reservedSeats = $this->tickets()
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_seats');
        return $this->seats - $reservedSeats;
    }*/
}
