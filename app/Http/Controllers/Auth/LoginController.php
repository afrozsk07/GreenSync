<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $remember = $request->has('remember'); // Check if "Remember Me" was checked
    
        // Debug: Log login attempt
        \Log::info('Login attempt for email: ' . $request->email);
        
        // Check if user exists first
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            \Log::info('User found: ' . $user->email . ', is_admin: ' . $user->is_admin);
            \Log::info('Password check result: ' . (Hash::check($request->password, $user->password) ? 'true' : 'false'));
        } else {
            \Log::info('User not found for email: ' . $request->email);
            return back()->withErrors(['email' => 'No account found with this email address.'])->onlyInput('email');
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $user = Auth::user();
            \Log::info('Login successful for user: ' . $user->email . ', is_admin: ' . $user->is_admin);
            if ($user->is_admin == 1) {
                return redirect()->route('admin.admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
    
        \Log::info('Login failed for email: ' . $request->email);
        return back()->withErrors(['password' => 'The password you entered is incorrect.'])->onlyInput('email');
    }
    

    public function logout(Request $request)
    {
        Auth::logout(); // logout the user

        $request->session()->invalidate(); // invalidate the session
        $request->session()->regenerateToken(); // regenerate CSRF token for security

        return redirect()->route('home'); // redirect to login page
    }
}
