<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Dashboard.dashboard');
    }

    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Redirect to login page with success message
        return redirect()->route('login.index')->with('success', 'You have been logged out successfully.');
    }
}
