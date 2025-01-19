<?php

use App\Http\Controllers\EventController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckOrganizerConfirmed;
use App\Http\Middleware\CheckOrganizerNotConfirmed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerLogowanie;
use App\Http\Controllers\ControllerRejestracja;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [EventController::class, 'index'])->name('events.index');

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

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/ticket/{event}', [App\Http\Controllers\TicketController::class, 'index'])->name('ticket.index');

    Route::post('/ticket/store', [App\Http\Controllers\TicketController::class, 'store'])->name('ticket.store');
});



Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');


Route::middleware(CheckAdmin::class)->group(function () {
//    Przenieśienie na stronę admina
    Route::get('/adminPanel', [App\Http\Controllers\AdminController::class, 'index'])->name('adminPanel');

//    Odmowa rejestracji jako organizator
    Route::post('/admin/reject/{id}', [App\Http\Controllers\AdminController::class, 'rejectOrganizer'])->name('admin.reject');

//    Akceptacja rejestracji jako organizator
    Route::post('/admin/confirm/{id}', [App\Http\Controllers\AdminController::class, 'confirmOrganizer'])->name('admin.confirm');

//    Akceptacja wydarzenia
    Route::post('admin/approveEvent/{id}', [App\Http\Controllers\AdminController::class, 'approveEvent'])->name('admin.approveEvent');

//    Odmowa wydarzenia
    Route::post('admin/rejectEvent/{id}', [App\Http\Controllers\AdminController::class, 'rejectEvent'])->name('admin.rejectEvent');
});

//  Przeniesienie niepotwierdzonego organizatora
Route::get('/organizerStatusInfo', [App\Http\Controllers\OrganizerController::class, 'indexNotConfirmed'])
    ->middleware(CheckOrganizerNotConfirmed::class)
    ->name('statusInfo');


Route::middleware(CheckOrganizerConfirmed::class)->group(function () {
    Route::get('/organizerPanel', [App\Http\Controllers\OrganizerController::class, 'indexConfirmed'])->name('organizer.panel');

    Route::post('/organizer/createEvent', [App\Http\Controllers\OrganizerController::class, 'createEvent'])->name('createEvent');

    Route::get('/organizer/createEvent', [App\Http\Controllers\OrganizerController::class, 'createEvent'])->name('createEvent');

    Route::post('/organizer/storeEvent', [App\Http\Controllers\OrganizerController::class, 'storeEvent'])->name('organizer.storeEvent');
});


/*Route::get('/adminPanel', function () {
    return view('adminPanel');
});*/
