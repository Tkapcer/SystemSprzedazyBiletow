<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexConfirmed()
    {
        $events = Event::where('organizer_id', Auth::guard('organizer')->user()->id)->orderBy('event_date', 'desc')->get();

        return view('organizer.panel', compact('events'));
    }

    public function indexNotConfirmed()
    {
        $organizer = auth()->guard('organizer')->user();

//        $user = auth()->user();

        return view('organizer.statusInfo', [
            'status' => $organizer->status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createEvent()
    {
        return view('organizer.createEvent');
    }

    // new!!!!!!!!!!!!!!!!!!!!!!!!
     /**
     * Show the report system.
     */
    public function organizerReportSystem()
    {
        return view('organizer.organizerReportSystem');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'event_date' => 'required|date',

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            'sectors.*.name' => 'required|string|max:255',
            'sectors.*.seats' => 'required|integer|min:1',
            'sectors.*.price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nazwa wydarzenia jest wymagana.',
            'name.string' => 'Nazwa wydarzenia musi być ciągiem znaków.',
            'name.max' => 'Nazwa wydarzenia nie może przekraczać 255 znaków.',
    
            'location.required' => 'Lokalizacja wydarzenia jest wymagana.',
            'location.string' => 'Lokalizacja musi być ciągiem znaków.',
            'location.max' => 'Lokalizacja nie może przekraczać 255 znaków.',
    
            'description.required' => 'Opis wydarzenia jest wymagany.',
            'description.string' => 'Opis musi być ciągiem znaków.',
            'description.max' => 'Opis nie może przekraczać 1000 znaków.',
    
            'event_date.required' => 'Data wydarzenia jest wymagana.',
            'event_date.date' => 'Proszę podać poprawny format daty.',
    
            'image.required' => 'Obrazek jest wymagany.',
            'image.image' => 'Proszę przesłać plik graficzny.',
            'image.mimes' => 'Dozwolone formaty obrazka to: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Rozmiar pliku obrazka nie może przekroczyć 2MB.',
    
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
            $imageFile = $request->file('image');
            $imagePath = $imageFile->store('uploads', 'public');
        }

        $organizer = auth()->guard('organizer')->user();

        $event = $organizer->events()->create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'image_path' => $imagePath,
            'status' => 'waiting'
        ]);

        foreach ($validated['sectors'] as $sectorData) {
            $event->sectors()->create($sectorData);
        }

        return redirect()->route('organizer.panel');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
