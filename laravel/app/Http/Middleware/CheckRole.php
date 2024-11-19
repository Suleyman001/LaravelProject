<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Detailed logging
        Log::info('Role Check Middleware', [
            'requested_roles' => $roles,
            'is_authenticated' => Auth::check(),
            'user_info' => Auth::check() ? [
                'id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ] : 'Not Authenticated'
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // Get current user's role
        $userRole = $request->user()->role;

        // Check if user's role is in the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt
        Log::warning('Unauthorized Access Attempt', [
            'user_role' => $userRole,
            'required_roles' => $roles,
            'requested_url' => $request->fullUrl()
        ]);

        // Unauthorized access
        return redirect('/')
            ->with('error', 'You do not have permission to access this page.');
    }
}