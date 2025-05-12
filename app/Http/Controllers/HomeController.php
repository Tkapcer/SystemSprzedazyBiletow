<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tickets = Ticket::with(['sector', 'event'])->where('user_id', Auth::id())->get();
        return view('home', ['tickets' => $tickets]);
    }

    public function addMoney(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'amount' => 'required|numeric|min:0.01|regex:/^\d+(\.\d{1,2})?$/'
        ], [
            'amount.required' => 'Kwota jest wymagana.',
            'amount.numeric' => 'Kwota musi być liczbą.',
            'amount.min' => 'Kwota musi być większa niż 0.',
            'amount.regex' => 'Kwota może mieć maksymalnie 2 miejsca po przecinku.',
        ]);

        $amout = $request->input('amount');

        $user->balance += $amout;
        $user->save();

        return redirect()->back()->with('success', 'Kwota została pomyślnie dodana do Twojego salda.');
    }
}
