<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // If they are a cashier trying to access Admin areas, send them to POS
            if (Auth::check() && Auth::user()->role === 'Cashier') {
                return redirect('/pos')->with('error', 'Access denied to administrative area.');
            }

            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
