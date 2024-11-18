<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerLogowanie;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [ControllerLogowanie::class, 'formularz']);
