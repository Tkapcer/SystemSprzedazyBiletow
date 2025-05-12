<?php

namespace App\Http\Middleware;

use Closure;


class ClearTransactionData
{
    public function handle($request, Closure $next)
    {
        $transactionRoutes = [
            'ticket.index',
            'ticket.summary',
            'ticket.store',
        ];

        if (!in_array($request->route()->getName(), $transactionRoutes)) {
            session()->forget('selectedSeats');
        }

        return $next($request);
    }
}
