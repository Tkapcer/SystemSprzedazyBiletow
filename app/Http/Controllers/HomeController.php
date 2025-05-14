<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Checkout\Session;

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

        $amount = $request->input('amount');
    /*
        $user->balance += $amout;
        $user->save();

        return redirect()->back()->with('success', 'Kwota została pomyślnie dodana do Twojego salda.');
        */
            Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'pln',
                    'product_data' => [
                        'name' => 'Doładowanie konta',
                    ],
                    'unit_amount' => $amount * 100,
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'success_url' => route('balanceSuccess', ['amount' => $amount]),
            'cancel_url' => url()->previous(),
        ]);

        return redirect($session->url);
    }

    public function balanceSuccess(Request $request)
    {
        $user = Auth::guard('web')->user();
        $amount = $request->query('amount');

        if (!$amount || !is_numeric($amount) || $amount <= 0) {
            return redirect()->route('home')->withErrors('Nieprawidłowa kwota.');
        }

        $user->balance += $amount;
        $user->save();

        return redirect()->route('home')->with('success', 'Saldo doładowane o: ' . number_format($amount, 2) . ' zł');
    }

}
