<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
//use GuzzleHttp\Psr7\Request;  //Wątpię, żeby o to chodziło. Raczej ma być to niżej
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //Events
    public function getTotalEvents()
    {
        $totalEvents = Event::count();
        return response()->json(['totalEvents' => $totalEvents]);
    }

    public function eventsSummaryReport(): JsonResponse
    {
        $statusMap = [
            'approved' => 'Nadchodzące',
            'expired' => 'Zakończone',
            'cancelled' => 'Anulowane'
        ];

        $events = Event::select('name', 'status', 'event_date')
            ->whereIn('status', array_keys($statusMap))
            ->orderBy('event_date', 'asc')
            ->get();

        $grouped = [
            'Nadchodzące' => [],
            'Zakończone' => [],
            'Anulowane' => []
        ];

        foreach ($events as $event) {
            $mappedStatus = $statusMap[$event->status];
            $grouped[$mappedStatus][] = $event->name;
        }

        $maxRows = max(array_map('count', $grouped));

        $rows = [];
        for ($i = 0; $i < $maxRows; $i++) {
            $rows[] = [
                'Nadchodzące' => $grouped['Nadchodzące'][$i] ?? '',
                'Zakończone' => $grouped['Zakończone'][$i] ?? '',
                'Anulowane' => $grouped['Anulowane'][$i] ?? '',
            ];
        }

        return response()->json([
            'tableData' => $rows,
            'chartData' => [
                'Nadchodzące' => count($grouped['Nadchodzące']),
                'Zakończone' => count($grouped['Zakończone']),
                'Anulowane' => count($grouped['Anulowane']),
            ]
        ]);
    }

    //Venues
    public function getTotalVenues()
    {
        $totalVenues = Venue::count();
        return response()->json(['totalVenues' => $totalVenues]);
    }

    public function getVenueDetails()
    {
        $venues = Venue::withCount('events')->get();
        return response()->json(['venues' => $venues]);
    }

    //Categories
    public function getTotalCategories()
    {
        $totalCategories = Category::count();   //To było wcześniej Categories jak coś, ale chyba tak ma być
        return response()->json(['totalCategories' => $totalCategories]);
    }

    public function getTotalRevenue(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['totalRevenue' => auth('organizer')->user()->revenue($from, $to)]);
    }

    public function getSoldTickets(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['soldTickets' => auth('organizer')->user()->soldTickers($from, $to)]);
    }

    public function getActiveReservations(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['totalRevenue' => auth('organizer')->user()->activeReservations($from, $to)]);
    }

}
