<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is not an admin
        if (Auth::check() && Auth::user()->is_admin == 0) {
            return $next($request);
        }

        // If not authenticated or is admin, redirect to login
        return redirect('/login')->with('error', 'Access denied. User access only.');
    }
}
