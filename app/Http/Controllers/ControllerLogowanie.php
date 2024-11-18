<?php

namespace App\Http\Controllers;

use http\Exception\BadConversionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\password;

class ControllerLogowanie extends Controller
{
    public function formularz()
    {
        return view('Logowanie');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            //
        } else {
            return back()->withErrors(['email' => 'zle dane']);
        }
    }
}
