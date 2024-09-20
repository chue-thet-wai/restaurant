<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $controllerAction = $route->getActionName();
        list($controller, $action) = explode('@', class_basename($controllerAction));

        $user = Auth::user();

        if ($user && $user->hasPermission($controller . '@' . $action)) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'You do not have permission to access this page');
    }
}
