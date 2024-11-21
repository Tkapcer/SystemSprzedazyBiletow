<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerLogowanie;
use App\Http\Controllers\ControllerRejestracja;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [ControllerLogowanie::class, 'formularz']);
Route::get('/test', [ControllerLogowanie::class, 'testowy']);

Route::get('/rejestracja', [ControllerRejestracja::class, 'stronaRejestracji']);
Route::post('/rejestracja', [ControllerRejestracja::class, 'rejestracja']);
//Route::get('/rejestracja', function (){return "cos";});
