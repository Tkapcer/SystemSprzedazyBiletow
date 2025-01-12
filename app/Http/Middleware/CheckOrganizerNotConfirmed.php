<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizerNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*if (Auth::check() && Auth::user()->type == 'organizer' && Auth::user()->organizerStatus != 'confirmed') {
            return $next($request);
        } else {
            return redirect()->route('login');
        }*/

        if (Auth::guard('organizer')->check() && Auth::guard('organizer')->user()->status != 'approved') {
            return $next($request);
        }
        return redirect()->route('organizer.panel');
    }
}
