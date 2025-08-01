<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminLoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('admin.adminlogin');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Debug: Log admin login attempt
        \Log::info('Admin login attempt for email: ' . $request->email);

        // Check if user exists first
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            \Log::info('Admin login failed: User not found for email: ' . $request->email);
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            \Log::info('Admin login successful for user: ' . $user->email . ', is_admin: ' . $user->is_admin);
            
            // Check if the user is an admin
            if ($user->is_admin == 1) {
                return redirect()->route('admin.admin.dashboard')->with('success', 'Welcome Admin!');
            } else {
                // If not admin, logout and redirect back with error
                Auth::logout();
                \Log::info('Non-admin user attempted admin login: ' . $user->email);
                return back()->withErrors([
                    'email' => 'Access denied. Admin credentials required.',
                ]);
            }
        }

        // If credentials don't match, redirect back with error
        \Log::info('Admin login failed for email: ' . $request->email);
        return back()->withErrors([
            'password' => 'The password you entered is incorrect.',
        ]);
    }

    // Logout the admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
