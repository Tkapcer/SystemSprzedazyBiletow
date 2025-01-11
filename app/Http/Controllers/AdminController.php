<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('adminPanel', [
//            'users' => User::where('type', 'organizer')->where('organizerStatus', 'waiting')->get()
            'users' => User::where('type', 'organizer')->get()
        ]);/*return view('adminPanel', [
            'users' => User::all()
        ]);*/
    }

    public function confirmOrganizer($id) {
        $user = User::findOrFail($id);
        $user->organizerStatus = 'confirmed';
        $user->save();
        return redirect()->back();
    }

    public function rejectOrganizer($id) {
        $user = User::findOrFail($id);
        $user->organizerStatus = 'rejected';
        $user->save();
        return redirect()->back()->with("Odmowa");
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
