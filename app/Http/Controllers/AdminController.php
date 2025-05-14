<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Category;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('adminPanel', [
            'venues' => Venue::all()->each(function ($venue) {
                $venue->hasActiveEvent = $venue->hasActiveEvents();
            }),
            'categories' => Category::all(), // <-- Add this key-value pair
            'organizers' => Organizer::orderByRaw("
                CASE
                    WHEN status = 'waiting' THEN 1
                    WHEN status = 'approved' THEN 2
                    WHEN status = 'rejected' THEN 3
                    ELSE 4
                END
            ")->get(),
            'events' => Event::orderByRaw("
                CASE
                    WHEN status = 'waiting' THEN 1
                    WHEN status = 'approved' THEN 2
                    WHEN status = 'rejected' THEN 3
                    WHEN status = 'expired' THEN 4
                    ELSE 5
                END
            ")->get()
        ]);
    }

    public function confirmOrganizer($id) {
        $organizer = Organizer::findOrFail($id);
        $organizer->status = 'approved';
        $organizer->save();
        return redirect()->back();
    }

    public function rejectOrganizer($id) {
        $organizer = Organizer::findOrFail($id);
        $organizer->status = 'rejected';
        $organizer->save();
        return redirect()->back()->with("Odmowa");
    }

    public function approveEvent($id) {
        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->save();
        return redirect()->back();
    }

    public function rejectEvent($id) {
        $event = Event::findOrFail($id);
        $event->status = 'rejected';
        $event->save();
        return redirect()->back();
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
        //
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
