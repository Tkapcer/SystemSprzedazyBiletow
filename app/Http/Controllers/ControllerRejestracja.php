<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ControllerRejestracja extends Controller
{
    public function stronaRejestracji()
    {
        return view('Rejestracja');
    }

    public function rejestracja(Request $request)
    {
        return $request->imie;
//        User::create()
    }
}
