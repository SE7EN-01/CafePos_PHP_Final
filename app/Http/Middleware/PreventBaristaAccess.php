<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBaristaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->hasRole('barista')) {
            abort(403);
        }

        return $next($request);
    }
}
