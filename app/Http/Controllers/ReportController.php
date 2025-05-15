<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;

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
        $totalCategories = Categories::count();
        return response()->json(['totalCategories' => $totalCategories]);
    }

}
