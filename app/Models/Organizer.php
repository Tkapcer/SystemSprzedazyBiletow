<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class Organizer extends Authenticatable
{
    use  HasFactory;

    protected $guarded = [];

    public function events() {
        return $this->hasMany(Event::class);
    }

    public function revenue($fromDate = null, $toDate = null) {
        $revenue = 0;

        $eventsQuery = $this->events()
            ->with(['sectors.tickets' => function ($query) {
                $query->where('status', 'purchased');
            }]);

        if ($fromDate) {
            $eventsQuery->where('updated_at', '>=', $fromDate);
        }

        if ($toDate) {
            $eventsQuery->where('updated_at', '<=', $toDate);
        }

        $events = $eventsQuery->get();

        foreach ($events as $event) {
            foreach ($event->sectors as $sector) {
                $price = $sector->getPriceForSeat($event->id);
                $ticketCount = $sector->tickets->count();
                $revenue += $ticketCount * $price;
            }
        }

        return $revenue;
    }

    public function soldTickers($fromDate = null, $toDate = null)
    {
        $eventsQuery = $this->events()
            ->with(['sectors.tickets' => function ($query) {
                $query->where('status', 'purchased');
            }]);

        if ($fromDate) {
            $eventsQuery->where('updated_at', '>=', $fromDate);
        }

        if ($toDate) {
            $eventsQuery->where('updated_at', '<=', $toDate);
        }

        $events = $eventsQuery->get();

        $soldTickets = 0;

        foreach ($events as $event) {
            foreach ($event->sectors as $sector) {
                $soldTickets += $sector->tickets->count();
            }
        }

        return $soldTickets;
    }

    public function activeReservations($fromDate = null, $toDate = null)
    {
        $eventsQuery = $this->events()
            ->with(['sectors.tickets' => function ($query) {
                $query->where('status', 'reservation');
            }]);

        if ($fromDate) {
            $eventsQuery->where('updated_at', '>=', $fromDate);
        }

        if ($toDate) {
            $eventsQuery->where('updated_at', '<=', $toDate);
        }

        $events = $eventsQuery->get();

        $activeReservations = 0;

        foreach ($events as $event) {
            foreach ($event->sectors as $sector) {
                $activeReservations += $sector->tickets->count();
            }
        }

        return $activeReservations;
    }
}
