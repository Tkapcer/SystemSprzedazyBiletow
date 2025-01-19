<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\Models\Event $event)
    {
        $sectors = $event->sectors;
        return view('ticket.index', compact('event', 'sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request);
        $request->validate([
            'sector_id' => 'required|exists:sectors,id',
            'number_of_seats' => 'required|integer|min:1',
            'status' => 'required|string|in:reserved,purchased'
        ]);

        $user = Auth::guard('web')->user();
        if (!$user) {   //Niby nie potrzebne, ale strzeżonego Pan Bóg strzeże xd
            return redirect()->route('login');
        }

        $sector = Sector::findOrFail($request->sector_id);
        if ($sector->availableSeats() < $request->number_of_seats) {
            return back()->withErrors('Brak wystarczającej liczby wolnych miejsc w wybranym sektorze.');
        }

        Ticket::create([
            'status' => $request->status,
            'user_id' => $user->id,
            'sector_id' => $sector->id,
            'number_of_seats' => $request->number_of_seats,
            'code' => strtoupper(Str::random(10))
        ]);

        $message = $request->status == 'purchased' ? 'Zakupiono bilet' : 'Zarezerwowano miejsce';
        return redirect()->route('events.index', $sector->event)->with('success', $message);
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
