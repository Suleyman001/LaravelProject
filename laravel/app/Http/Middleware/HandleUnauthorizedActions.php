<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUnauthorizedActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/HandleUnauthorizedActions.php
public function handle($request, Closure $next)
{
    try {
        return $next($request);
    } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
        // Redirect with error message
        return redirect()->back()
            ->with('error', 'You are not authorized to perform this action.');
    }
}
}
