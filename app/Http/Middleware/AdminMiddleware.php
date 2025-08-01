<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.admin-login')->with('error', 'Please login to access admin panel.');
        }

        // Check if user is an admin
        if (Auth::user()->is_admin != 1) {
            Auth::logout();
            return redirect()->route('admin.admin-login')->with('error', 'Access denied. Admin credentials required.');
        }

        return $next($request);
    }
}
