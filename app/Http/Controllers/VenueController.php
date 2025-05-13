<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sectors.*.name' => 'required|string',
            'sectors.*.rows' => 'required|integer|min:1',
            'sectors.*.columns' => 'required|integer|min:1',
        ]);

        $venue = Venue::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? '',
        ]);

        foreach ($validated['sectors'] as $sector) {
            $venue->sectors()->create($sector);
        }

        return redirect()->route('venues.create')->with('status', 'Dodano nową lokalizacje');
    }
}
