<?php

use App\Http\Controllers\EventController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckOrganizerConfirmed;
use App\Http\Middleware\CheckOrganizerNotConfirmed;
use App\Http\Middleware\ClearTransactionData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//  Do usuwania danych o wybranych miejscach, jeśli użytkownik zrezygnuje z zakupu
Route::middleware(ClearTransactionData::class)->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');

    Auth::routes();

//    Zalogowany jako user
    Route::middleware('auth')->group(function () {
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//    Dodanie nowych środków
        Route::post('/addMoney', [App\Http\Controllers\HomeController::class, 'addMoney'])->name('addMoney');

//    Strona z zakupem biletu
        Route::get('/ticket/{event}', [App\Http\Controllers\TicketController::class, 'index'])->name('ticket.index');

//    Strona z podsumowaniem zakupu biletu
        Route::post('/ticket/summary', [App\Http\Controllers\TicketController::class, 'summary'])->name('ticket.summary');

//    Zapis biletu
        Route::post('/ticket/store', [App\Http\Controllers\TicketController::class, 'store'])->name('ticket.store');

//    Anulowanie rezerwacji
        Route::post('/ticket/cancel/{id}', [App\Http\Controllers\TicketController::class, 'cancel'])->name('ticket.cancel');

//    Opłacenie biletu
        Route::post('/ticket/pay/{id}', [App\Http\Controllers\TicketController::class, 'pay'])->name('ticket.pay');

//    Zwracanie biletu
        Route::post('/ticket/return/{id}', [App\Http\Controllers\TicketController::class, 'return'])->name('ticket.return');
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
        Route::get('admin/rejectEvent/{id}', [App\Http\Controllers\AdminController::class, 'rejectEvent'])->name('admin.rejectEvent');
    });

//  Przeniesienie niepotwierdzonego organizatora
    Route::get('/organizerStatusInfo', [App\Http\Controllers\OrganizerController::class, 'indexNotConfirmed'])
        ->middleware(CheckOrganizerNotConfirmed::class)
        ->name('statusInfo');


    Route::middleware(CheckOrganizerConfirmed::class)->group(function () {
        Route::get('/organizerPanel', [App\Http\Controllers\OrganizerController::class, 'indexConfirmed'])->name('organizer.panel');

        Route::get('/organizer/report', [App\Http\Controllers\OrganizerController::class, 'indexReport'])->name('report');

        Route::post('/organizer/createEvent', [App\Http\Controllers\OrganizerController::class, 'createEvent'])->name('createEvent');

        Route::get('/organizer/createEvent', [App\Http\Controllers\OrganizerController::class, 'createEvent'])->name('createEvent');

        Route::post('/organizer/storeEvent', [App\Http\Controllers\OrganizerController::class, 'storeEvent'])->name('organizer.storeEvent');

        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('editEvent');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('updateEvent');

        Route::get('/events/{event}/cancel', [EventController::class, 'cancel'])->name('cancelEvent');
    });
});
