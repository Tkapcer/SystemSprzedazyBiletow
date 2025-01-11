<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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


    public function redirectTo()
    {
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
