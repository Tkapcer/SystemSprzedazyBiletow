<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function eventsSummaryReport(): JsonResponse
    {
        // Pobieramy tylko te statusy, które nas interesują
        $statusMap = [
            'approved' => 'Nadchodzące',
            'expired' => 'Zakończone',
            'cancelled' => 'Anulowane'
        ];

        $events = Event::select('name', 'status', 'event_date')
            ->whereIn('status', array_keys($statusMap))
            ->orderBy('event_date', 'asc')
            ->get();

        // Grupowanie nazw wydarzeń według statusów z mapowaniem
        $grouped = [
            'Nadchodzące' => [],
            'Zakończone' => [],
            'Anulowane' => []
        ];

        foreach ($events as $event) {
            $mappedStatus = $statusMap[$event->status];
            $grouped[$mappedStatus][] = $event->name;
        }

        // Ustal maksymalną liczbę wierszy
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
}
