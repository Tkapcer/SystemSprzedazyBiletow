<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NowyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nowa', function () {
    return view('nowa');
});

Route::get('/controler', [NowyController::class, 'show']);

