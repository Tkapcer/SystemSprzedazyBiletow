<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NowyController extends Controller
{
    public function show() {
        return view('welcome');
}
}
