<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        // Walidacja
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Próba logowania usera
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
//            to też kidyś na bool
            if (Auth::user()->type == 'admin') {
                return redirect()->intended(route('adminPanel'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        // Próba logowania organizatora
        if (Auth::guard('organizer')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended(route('statusInfo'));
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('organizer')->check()) {
            Auth::guard('organizer')->logout();
        }

        return redirect('/');
    }


    public function redirectTo()
    {
        /*if ($request->has('organizerForm')) {

        } else {
            $user = auth()->user();

//            To by kiedyś można na bool przerobić, ale to jak się będzie bazę przerabiało
            if ($user->type == 'admin') {
                return '/adminPanel';
            }
        }*/

        $user = auth()->user();

        if ($user->type == 'admin') {
            return '/adminPanel';
        } else if ($user->type == 'user') {
            return 'home';
        } else if ($user->type == 'organizer') {
            if ($user->organizerStatus == 'confirmed') {
                return '/organizerPanel';
            } else {
                return '/organizerStatusInfo';
            }
        }

        return '/';
    }
}
