<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DiagnoseRouteAccess
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $route = $request->route();

        Log::info('Route Access Attempt', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $user ? $user->id : 'Guest',
            'user_role' => $user ? $user->role : 'None',
            'is_authenticated' => auth()->check(),
            'route_name' => $route ? $route->getName() : 'No Route Name',
            'middleware' => $route ? $route->middleware() : 'No Middleware',
            'view_exists' => view()->exists('notebooks.create'),
            'controller_action' => $route ? $route->getActionName() : 'No Action'
        ]);

        return $next($request);
    }
}