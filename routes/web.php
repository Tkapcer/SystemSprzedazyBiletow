<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerLogowanie;
use App\Http\Controllers\ControllerRejestracja;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logowanie', [ControllerLogowanie::class, 'formularz']);
Route::get('/test', [ControllerLogowanie::class, 'testowy']);

Route::get('/rejestracja', [ControllerRejestracja::class, 'stronaRejestracji']);
Route::post('/rejestracja', [ControllerRejestracja::class, 'rejestracja']);
//Route::get('/rejestracja', function (){return "cos";});

Auth::routes();

//Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
//Route::post('login', [LoginController::class, 'login']);
//Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//
//Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
//Route::post('register', [RegisterController::class, 'register']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(CheckAdmin::class)->group(function () {
//    Przenieśienie na stronę admina
    Route::get('/adminPanel', [App\Http\Controllers\AdminController::class, 'index'])->name('adminPanel');

//    Odmowa rejestracji jako organizator
    Route::post('/admin/reject/{id}', [App\Http\Controllers\AdminController::class, 'rejectOrganizer'])->name('admin.reject');

//    Akceptacja rejestracji jako organizator
    Route::post('/admin/confirm/{id}', [App\Http\Controllers\AdminController::class, 'confirmOrganizer'])->name('admin.confirm');
});


/*Route::get('/adminPanel', function () {
    return view('adminPanel');
});*/
