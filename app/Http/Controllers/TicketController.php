<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Sector;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function PHPSTORM_META\map;

class TicketController extends Controller
{
    /*public function getAvailableSeats(int $event_id, int $sector_id)
    {
        $sector = Sector::findOrFail($sector_id);
        $allSeats = $sector->allSeats();

        $takenSeats = Ticket::where('event_id', $event_id)->where('sector_id', $sector_id)->get(['row', 'column']);

        $availableSeats = $allSeats->reject(function ($seat) use ($takenSeats) {
            return $takenSeats->contains(function ($takenSeat) use ($seat) {
                return $takenSeat->row == $seat['row'] && $takenSeat->column == $seat['column'];
            });
        });

    }*/

    /**
     * Display a listing of the resource.
     */
    public function index(\App\Models\Event $event)
    {
        /*$sectors = $event->sectors->filter(function ($sector) {
            return $sector->availableSeats() > 0;
        });*/
        $sectors = $event->sectors;

        $sectorsWithSeats = $sectors->map(function ($sector) use ($event) {
           $allSeats = $sector->getAllSeats($event->id);
           return ['sector' => $sector, 'seats' => $allSeats];
        });

        return view('ticket.index', compact('event', 'sectorsWithSeats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function summary(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'selected_seats' => 'required|array',
            'selected_seats.*.*' => ['required', 'regex:/^\d+-\d+$/']
        ], [
            'selected_seats.required' => 'Proszę wybrać przynajmniej jedno miejsce.',
            'selected_seats.*.*.required' => 'Proszę wybrać miejsca.',
            'selected_seats.*.*.regex' => 'Błędne dane wybranego miejsca.'
        ]);

        $eventId = $request->input('eventId');
        $event = Event::findOrFail($eventId);
        $selectedSeatsByCoordinates = $request->input('selected_seats');
        $selectedSeats = [];
        $totalPrice = 0;

        foreach ($selectedSeatsByCoordinates as $sectorId => $seatsCoordinates) {
            $sector = Sector::findOrFail($sectorId);
            $price = $sector->getPriceForSeat($eventId);

            foreach ($seatsCoordinates as $seatCoordinates) {
                list($row, $colum) = explode('-', $seatCoordinates);
                $totalPrice += $price;

                $seat  = new Seat(
                    id: -1,
                    eventId: $eventId,
                    sectorId: $sectorId,
                    row: $row,
                    column: $colum,
                    price: $price,
                );

                $selectedSeats[] = $seat;
            }
        }

        session(['selectedSeats' => $selectedSeats]);
//        dd(session('selectedSeats'));

        return view('ticket.summary', compact('event', 'selectedSeats', 'totalPrice'));
    }
    public function store(Request $request) {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_STARY_NIE_UZYWANY(Request $request)
    {
//        dd($request->all());
        // Sprawdzenie, czy 'sectors' zostało przekazane w żądaniu
        if (!$request->has('sectors')) {
            return back()->withErrors('Brak danych dotyczących sektorów.');
        }

        // Dekodujemy "sectors" na tablicę
        $decodedSectors = json_decode($request->input('sectors'), true);
        $request->merge(['sectors' => $decodedSectors]);

        $request->validate([
            'sectors' => 'required|min:1',
            'sectors.*.sector_id' => 'required|exists:sectors,id',
            'sectors.*.number_of_seats' => 'required|integer|min:1',
            'status' => 'required|string|in:reserved,purchased'
        ],[
            'sectors.required' => 'Proszę podać sektory.',
            'sectors.min' => 'Musisz wybrać co najmniej jeden sektor.',
            'sectors.*.sector_id.required' => 'Proszę wybrać sektor.',
            'sectors.*.sector_id.exists' => 'Wybrany sektor nie istnieje.',
            'sectors.*.number_of_seats.required' => 'Proszę podać liczbę miejsc.',
            'sectors.*.number_of_seats.integer' => 'Liczba miejsc musi być liczbą całkowitą.',
            'sectors.*.number_of_seats.min' => 'Liczba miejsc musi wynosić co najmniej 1.',
            'status.required' => 'Proszę wybrać status rezerwacji.',
            'status.string' => 'Status rezerwacji musi być ciągiem znaków.',
            'status.in' => 'Status rezerwacji może przyjmować jedynie wartości: "reserved" lub "purchased".'
        ]);


        $user = Auth::guard('web')->user();
        if (!$user) {   //Niby nie potrzebne, ale strzeżonego Pan Bóg strzeże xd
            return redirect()->route('login');
        }

        foreach ($decodedSectors as $sectorData) {
            $sector = Sector::findOrFail($sectorData['sector_id']);
           /* if ($sector->availableSeats() < $sectorData['number_of_seats']) {
                return back()->withErrors('Brak wystarczającej liczby wolnych miejsc w wybranym sektorze.');
            }*/
//            dd($sectorData);

            if ($request->status == 'purchased') {
                if ($user->balance >= $sectorData['number_of_seats'] * $sector->price) {
                    $user->balance -= $sectorData['number_of_seats'] * $sector->price;
                    $user->save();
                } else {
                    return back()->withErrors('Brak wystarczających środków na koncie.');
                }
            }

            Ticket::create([
                'status' => $request->status,
                'user_id' => $user->id,
                'sector_id' => $sector->id,
                'number_of_seats' => $sectorData['number_of_seats'],
                'code' => $request->status == 'purchased' ? strtoupper(Str::random(10)) : "",
            ]);
        }

        $message = $request->status == 'purchased' ? 'Zakupiono bilet' : 'Zarezerwowano miejsce';
        return redirect()->route('home', $sector->event)->with('success', $message);
    }

    public function cancel(Request $request) {
        $user = Auth::guard('web')->user();
        $ticket = Ticket::where('id', $request->id)->where('user_id', $user->id)->first();

        if (!$ticket) {
            return redirect()->back()->withErrors('Nie masz tej rezerwacji.');
        }

        $ticket->delete();
        return redirect()->back()->with('success', 'Anulowano rezerwację');
    }

    public function return(Request $request) {
        $user = Auth::guard('web')->user();
        $ticket = Ticket::where('id', $request->id)
            ->where('user_id', $user->id)
            ->where('status', 'purchased')
            /*->whereHas('sector.event', function ($query) {
                $query->where('status', 'approved');
            })*/
            ->first();

        if (!$ticket ) {
            return redirect()->back()->withErrors('Nie masz tego biletu.');
        }

        DB::transaction(function () use ($ticket, $user) {
            $user->balance += $ticket->number_of_seats * $ticket->sector->price;
            $user->save();
            $ticket->delete();
        });

        return redirect()->back()->with('success', 'Zwrócono bilet i zwrócono środki.');
    }

    public function pay(Request $request) {
        $user = Auth::guard('web')->user();
        $ticket = Ticket::where('id', $request->id)
            ->where('user_id', $user->id)
            /*->whereHas('sector.event', function ($query) {
                $query->where('status', 'approved');
            })*/
            ->first();

        if (!$ticket) {
            return redirect()->back()->withErrors('Nie masz tej rezerwacji.');
        }

        if ($user->balance < $ticket->number_of_seats * $ticket->sector->price) {
            return redirect()->back()->withErrors('Brak wystarczających środków na koncie.');
        }

        DB::transaction(function () use ($ticket, $user) {
           $user->balance -= $ticket->number_of_seats * $ticket->sector->price;
           $user->save();
           $ticket->status = 'purchased';
           $ticket->code = strtoupper(Str::random(10));
           $ticket->save();
        });

        return redirect()->back()->with('success', 'Opłacono bilet.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
