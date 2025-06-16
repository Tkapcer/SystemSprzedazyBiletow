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

    public function revenue($event_id = null, $fromDate = null, $toDate = null) {
        $revenue = 0;

        $eventsQuery = $this->events();

        if ($event_id) {
            $eventsQuery->where('id', $event_id);
        }

        $events = $eventsQuery->with('sectors')->get();

        foreach ($events as $event) {
            foreach ($event->sectors as $sector) {
                $price = $sector->getPriceForSeat($event->id);
                $ticketQuery = Ticket::where('sector_id', $sector->id)
                    ->where('event_id', $event->id)
                    ->where('status', 'purchased');

                if ($fromDate) {
                    $ticketQuery->where('updated_at', '>=', $fromDate);
                }
                if ($toDate) {
                     $ticketQuery->where('updated_at', '<=', $toDate);
                }

                $ticketCount = $ticketQuery->count();
                $revenue += $ticketCount * $price;
            }
        }

        return $revenue;
    }

    public function soldTickers($event_id = null, $fromDate = null, $toDate = null, $minRevenue = null)
    {
        $eventsQuery = $this->events();

        if ($event_id) {
            $eventsQuery->where('id', $event_id);
        }

        $events = $eventsQuery->with('sectors')->get();

        $soldTickets = 0;

        foreach ($events as $event) {
            if ($minRevenue && $this->revenue($event->id, $fromDate, $toDate) < $minRevenue) {
                continue;
            }
            foreach ($event->sectors as $sector) {
                $ticketQuery = Ticket::where('sector_id', $sector->id)
                    ->where('event_id', $event->id)
                    ->where('status', 'purchased');

                if ($fromDate) {
                    $ticketQuery->where('updated_at', '>=', $fromDate);
                }
                if ($toDate) {
                    $ticketQuery->where('updated_at', '<=', $toDate);
                }

                $soldTickets += $ticketQuery->count();
            }
        }

        return $soldTickets;
    }

    public function activeReservations($event_id = null, $fromDate = null, $toDate = null, $minRevenue = null)
    {
        $eventsQuery = $this->events();

        if ($event_id) {
            $eventsQuery->where('id', $event_id);
        }

        $events = $eventsQuery->with('sectors')->get();

        $activeReservations = 0;

        foreach ($events as $event) {
            if ($minRevenue && $this->revenue($event->id, $fromDate, $toDate) < $minRevenue) {
                continue;
            }
            foreach ($event->sectors as $sector) {
                $ticketQuery = Ticket::where('sector_id', $sector->id)
                    ->where('event_id', $event->id)
                    ->where('status', 'reserved');

                if ($fromDate) {
                    $ticketQuery->where('updated_at', '>=', $fromDate);
                }
                if ($toDate) {
                    $ticketQuery->where('updated_at', '<=', $toDate);
                }

                $activeReservations += $ticketQuery->count();
            }
        }

        return $activeReservations;
    }
}
