<?php

namespace App\Http\Middleware;

use App\Models\LoginQueue;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckQueue
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if(!$user) {
            return $next($request);
        }

        $activeUser = LoginQueue::where('position', 1)->first();
        if(!$activeUser) {
            LoginQueue::create([
                'user_id' => $user->id,
                'position' => 1,
            ]);

            return $next($request);
        }

        if ($activeUser->user_id == $user->id) {
            return $next($request);
        }

        $userQueue = LoginQueue::firstOrCreate(
            ['user_id' => $user->id],
            ['position' => LoginQueue::max('position') + 1]
        );

        return response()->view('queue', [
            'position' => $userQueue->position - 1,
        ]);
    }
}
