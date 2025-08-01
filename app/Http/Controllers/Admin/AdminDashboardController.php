<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is an admin
            if ($user->is_admin == 1) {
                return view('admin.dashboard');
            } else {
                // If not admin, redirect to user dashboard
                return redirect()->route('dashboard')->with('error', 'Access denied. Admin access required.');
            }
        } else {
            // If not authenticated, redirect to login
            return redirect()->route('login');
        }
    }
}
