<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureDirectBladeVisit
{
    /**
     * Handle an incoming request.
     *
     * If an Inertia request tries to load this Blade route, force a hard browser reload
     * to prevent Inertia from rendering the Blade layout inside a modal preview dialog.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location($request->fullUrl());
        }

        return $next($request);
    }
}
