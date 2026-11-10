<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventPublicRegistration
{
    /**
     * Handle an incoming request.
     * Blocks /register GET and POST unless FORTIFY_REGISTRATION is enabled.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('register') && ! (bool) env('FORTIFY_REGISTRATION', false)) {
            abort(404);
        }

        return $next($request);
    }
}
