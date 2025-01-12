<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexConfirmed()
    {
        return view('organizer.panel');
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
