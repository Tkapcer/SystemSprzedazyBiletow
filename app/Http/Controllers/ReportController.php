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
        $venues = Venue::with('events')->get();

        $venueDetails = [];

        foreach ($venues as $venue) {
            $venueDetails[$venue->name] = $venue->events->count();
        }
        return response()->json($venueDetails);
    }

    //Categories
    public function getTotalCategories()
    {
        $totalCategories = Category::count();   //To było wcześniej Categories jak coś, ale chyba tak ma być
        return response()->json(['totalCategories' => $totalCategories]);
    }

     public function getCategoryDetails()
    {
        $categories = Category::withCount('events')->get();
        return response()->json(['categories' => $categories]);
    }

    public function getTotalRevenue(Request $request)
    {
        $event_id = $request->query('event_id');
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['totalRevenue' => auth('organizer')->user()->revenue($event_id, $from, $to)]);
    }

    public function getRevenueByEvent(Request $request) {
        $from = $request->query('from');
        $to = $request->query('to');
        $minRevenue = $request->query('min_revenue');

        $user = auth('organizer')->user();
        $events = Event::where('organizer_id', $user->id)->get();

        $revenueByEvent = [];

        foreach ($events as $event) {
            if ($minRevenue <= $user->revenue($event->id, $from, $to)) {
                $revenueByEvent[$event->name] = $user->revenue($event->id, $from, $to);
            }
        }

        return response()->json(['revenueByEvent' => $revenueByEvent]);
    }

    public function getSoldTickets(Request $request)
    {
        $event_id = $request->query('event_id');
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['soldTickets' => auth('organizer')->user()->soldTickers($event_id, $from, $to)]);
    }

    public function getSoldTicketsByEvent() {
        $user = auth('organizer')->user();
        $events = Event::where('organizer_id', $user->id)->get();

        $soldTicketsByEvent = [];

        foreach ($events as $event) {
            $soldTicketsByEvent[$event->name] = $user->soldTickers($event->id);
        }

        return response()->json(['soldTicketsByEvent' => $soldTicketsByEvent]);
    }

    public function getActiveReservations(Request $request)
    {
        $event_id = $request->query('event_id');
        $from = $request->query('from');
        $to = $request->query('to');

        return response()->json(['activeReservations' => auth('organizer')->user()->activeReservations($event_id, $from, $to)]);
    }

    public function getActiveReservationsByEvent() {
        $user = auth('organizer')->user();
        $events = Event::where('organizer_id', $user->id)->get();

        $activeReservationsByEvent = [];

        foreach ($events as $event) {
            $activeReservationsByEvent[$event->name] = $user->activeReservations($event->id);
        }

        return response()->json(['activeReservationsByEvent' => $activeReservationsByEvent]);
    }

    public function getAverageOccupancy() {
        $occupancy = 0;

        $events = Event::where('organizer_id', auth('organizer')->user()->id)
            ->whereIn('status', ['approved', 'expired'])->get();

        foreach ($events as $event) {
            $occupancy += $event->occupancy();
        }

        return response()->json([
            'averageOccupancy' => count($events) > 0 ? ($occupancy / count($events)) : 0
        ]);
    }

    public function getOccupancyByEvent() {
        $occupancy = [];

        $events = Event::where('organizer_id', auth('organizer')->user()->id)
            ->whereIn('status', ['approved', 'expired'])->get();

        foreach ($events as $event) {
            $occupancy[$event->name] = $event->occupancy();
        }

        return response()->json($occupancy);
    }

    // For chart with detailed filtered data

    public function getFilteredData(Request $request)
    {
        $user = auth('organizer')->user();

        $dataType = $request->input('dataType', 'revenue');
        $eventId = $request->input('eventId');
        $categoryId = $request->input('categoryId');
        $venueId = $request->input('venueId');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $minRevenue = $request->input('minRevenue');

        $events = Event::query()
            ->where('organizer_id', $user->id);

        if ($eventId) {
            $events->where('id', $eventId);
        }
        if ($categoryId) {
            $events->where('category_id', $categoryId);
        }
        if ($venueId) {
            $events->where('venue_id', $venueId);
        }
        /*if ($startDate) {
            $events->whereDate('event_date', '>=', $startDate);
        }
        if ($endDate) {
            $events->whereDate('event_date', '<=', $endDate);
        }*/
//        komentarz bo cos mi commit nie działa
        $resultData = [];

        foreach ($events->get() as $event) {
            $label = $event->name;

            switch ($dataType) {
                case 'revenue':
                    $value = $user->revenue($event->id, $startDate, $endDate);
                    $labelText = 'Dochód (zł)';
                    $formatter = 'currency';
                    break;

                case 'tickets':
                    $value = $user->soldTickers($event->id, $startDate, $endDate);
                    $labelText = 'Sprzedane bilety';
                    $formatter = null;
                    break;

                case 'reservations':
                    $value = $user->activeReservations($event->id, $startDate, $endDate);
                    $labelText = 'Rezerwacje';
                    $formatter = null;
                    break;

                case 'occupancy':
                    $value = $event->occupancy();
                    $labelText = 'Obłożenie (%)';
                    $formatter = 'percent';
                    break;

                default:
                    $value = 0;
                    $labelText = 'Dane';
                    $formatter = null;
            }

            if ($dataType === 'revenue' && $minRevenue !== null && $value < $minRevenue) {
                continue;
            }

            $resultData[] = [
                'label' => $label,
                'value' => $value
            ];
        }

        return response()->json([
            'label' => $labelText,
            'formatter' => $formatter,
            'data' => $resultData
        ]);
    }

    public function getEventsDropdown()
    {
        $events = Event::where('organizer_id', auth('organizer')->id())
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
        return response()->json($events);
    }

    public function getCategoriesDropdown()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return response()->json($categories);
    }

    public function getVenuesDropdown()
    {
        $venues = Venue::select('id', 'name')->orderBy('name')->get();
        return response()->json($venues);
    }

}
