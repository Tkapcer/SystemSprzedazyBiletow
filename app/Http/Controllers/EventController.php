<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sector;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jeśli user jest adminem, chcemy żeby widział wszystkie eventy
        if (Auth::guard('admin')->check()) {
            $events = Event::orderBy('event_date')->get();
        } else {
            $events = Event::where('status', 'approved')->orderBy('event_date')->get();
        }

        return view('welcome', [
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('...', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Walidacja danych
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'event_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            'sectors' => 'required|array|min:1',
            'sectors.*.name' => 'required|string|max:255',
            'sectors.*.seats' => 'required|integer|min:1',
            'sectors.*.price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ], [
            'name.required' => 'Nazwa wydarzenia jest wymagana.',
            'name.string' => 'Nazwa wydarzenia musi być ciągiem znaków.',
            'name.max' => 'Nazwa wydarzenia nie może przekraczać 255 znaków.',

            'description.required' => 'Opis wydarzenia jest wymagany.',
            'description.string' => 'Opis musi być ciągiem znaków.',
            'description.max' => 'Opis nie może przekraczać 1000 znaków.',

            'event_date.required' => 'Data wydarzenia jest wymagana.',
            'event_date.date' => 'Proszę podać poprawny format daty.',

            'image.required' => 'Obrazek jest wymagany.',
            'image.image' => 'Proszę przesłać plik graficzny.',
            'image.mimes' => 'Dozwolone formaty obrazka to: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Rozmiar pliku obrazka nie może przekroczyć 2MB.',

            'sectors.required' => 'Proszę dodać przynajmniej jeden sektor.',
            'sectors.array' => 'Sekcje muszą być w formie tablicy.',
            'sectors.min' => 'Wydarzenie musi mieć co najmniej jeden sektor.',

            'sectors.*.name.required' => 'Nazwa sektora jest wymagana.',
            'sectors.*.name.string' => 'Nazwa sektora musi być ciągiem znaków.',
            'sectors.*.name.max' => 'Nazwa sektora nie może przekraczać 255 znaków.',

            'sectors.*.seats.required' => 'Liczba miejsc w sektorze jest wymagana.',
            'sectors.*.seats.integer' => 'Liczba miejsc musi być liczbą całkowitą.',
            'sectors.*.seats.min' => 'Liczba miejsc musi być większa niż 0.',

            'sectors.*.price.required' => 'Cena za miejsce w sektorze jest wymagana.',
            'sectors.*.price.numeric' => 'Cena musi być liczbą.',
            'sectors.*.price.min' => 'Cena nie może być mniejsza niż 0.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // Tworzenie wydarzenia
        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'image_path' => $imagePath,
        ]);

        // Tworzenie sektorów przypisanych do wydarzenia
        foreach ($validated['sectors'] as $sectorData) {
            $event->sectors()->create($sectorData);
        }
        // Przypisanie kategorii do wydarzenia
        $categories = Arr::wrap($validated['categories'] ?? []);
        $event->categories()->sync($categories);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
   public function show(Event $event)
    {
        // Allow admin to see any event, others only see approved
        if (Auth::guard('admin')->check()) {
            $event->load('organizer');
            return view('event.show', ['event' => $event]);
        }

        if ($event->status == 'approved') {
            $event->load('organizer');
            return view('event.show', ['event' => $event]);
        } else {
            return redirect()->route('events.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
{
    $categories = Category::all();
    return view('...', compact('categories'));

    if ($event->status == 'cancelled') {
        return redirect()->back()->withErrors('Nie można edytować anulowanych wydarzeń');
    } else if ($event->status == 'expired') {
        return redirect()->back()->withErrors('Nie można edytować odbytych wydarzeń');
    } else {
        // Pobierz wszystkie sale z sektorami

        $venues = Venue::with('sectors')->get();

        // Upewnij się, że sektory wydarzenia zawierają pivot z ceną
        $event->load(['sectors' => function ($query) {
            $query->withPivot('price');
        }]);

        return view('organizer.editEvent', compact('event', 'venues'));
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->status == 'cancelled') {
            return redirect()->route('organizer.panel')->withErrors('Nie można edytować anulowanych wydarzeń');
        } else if ($event->status == 'expired') {
            return redirect()->back()->withErrors('Nie można edytować odbytych wydarzeń');
        }
//        dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ], [
            'name.required' => 'Nazwa wydarzenia jest wymagana.',
            'name.string' => 'Nazwa wydarzenia musi być ciągiem znaków.',
            'name.max' => 'Nazwa wydarzenia nie może przekraczać 255 znaków.',

            'description.required' => 'Opis wydarzenia jest wymagany.',
            'description.string' => 'Opis musi być ciągiem znaków.',
            'description.max' => 'Opis nie może przekraczać 1000 znaków.',

            'event_date.required' => 'Data wydarzenia jest wymagana.',
            'event_date.date' => 'Proszę podać poprawny format daty.',

            'image.required' => 'Obrazek jest wymagany.',
            'image.image' => 'Proszę przesłać plik graficzny.',
            'image.mimes' => 'Dozwolone formaty obrazka to: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Rozmiar pliku obrazka nie może przekroczyć 2MB.',
        ]);

        DB::transaction(function () use ($validated, $event, $request) {
            // Obsługa obrazu
            $imagePath = $event->image_path;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
            }

            // Aktualizowanie danych wydarzenia
            $event->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'event_date' => $validated['event_date'],
                'image_path' => $imagePath,
                // Status automatycznie ustawiany na "waiting"
                'status' => 'waiting'
            ]);

            $event->categories()->sync($categories);
        });

        return redirect()->route('organizer.panel')->with('success', 'Wydarzenie zostało zaktualizowane.');
    }

    public function cancel(Event $event)
    {
        if ($event->status != 'cancelled' && $event->status != 'expired') {
            DB::transaction(function () use ($event) {
                $sectors = Sector::where('event_id', $event->id)->get();
                foreach ($sectors as $sector) {
                    $tickets = Ticket::with('user')->where('sector_id', $sector->id)->get();
                    foreach ($tickets as $ticket) {
//                        $user = User::where('id', $ticket->user_id)->get();
                        if ($ticket->status == 'purchased') {
                            $ticket->user->balance += $ticket->sector->price * $ticket->number_of_seats;
                            $ticket->user->save();
                        }
                        $ticket->sector->event->status = 'cancelled';
//                        $ticket->status = 'cancelled';
                        $ticket->sector->event->save();
                    }
                    $sector->seats=-1;
                    $sector->save();
                }

                /*$tickets = Ticket::with(['user', 'sector'])->where('event_id', $event->id)->get();
                foreach ($tickets as $ticket) {
                    if ($ticket->status == 'purchased') {
                        $ticket->user->balance += $ticket->sector->price * $ticket->number_of_seats;
                        $ticket->user->save();
                    }
                    $ticket->status = 'cancelled';
                    // Tu i tak brakuje usuwania sektorów jak coś
                }*/

                $event->status='cancelled';
                $event->save();
            });
        } else {
            return redirect()->route('organizer.panel')->with('error', 'Wydarzenie nie istnieje.');
        }
        return redirect()->route('organizer.panel')->with('success', 'Wydarzenie zostało usunięte.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
