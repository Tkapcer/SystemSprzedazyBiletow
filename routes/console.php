<?php

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('UpdateEventsStatus', function () {
//    \Log::info("Updating events status");
    $expiredEvents = Event::where('status', 'approved')
        ->where('event_date', '<', Carbon::now());
    foreach ($expiredEvents->get() as $event) {
        $event->status = 'expired';
        $event->save();
    }
})->purpose('Ustawienie statusu odbytych wydarzeń')->everyMinute();
