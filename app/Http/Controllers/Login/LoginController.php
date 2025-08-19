<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\RegisteredUsers;

class LoginController extends Controller
{
    public function index()
    {
        return view('Login.login');
    }

    public function register()
    {
        return view('Login.register');
    }

    public function store(Request $request)
    {
        // Validate registration form
        $request->validate([
            'fullname'    => 'required|string|max:255|unique:registered_users,fullname',
            'student_no'  => 'required|string|max:20|unique:registered_users,student_no',
            'email'       => 'required|string|email|max:255|unique:registered_users,email',
        ]);

        // Save user as pending without password
        $user = new RegisteredUsers();
        $user->fullname       = $request->fullname;
        $user->address        = $request->address ?? null; // Optional fiel
        $user->student_no     = $request->student_no;
        $user->email          = $request->email;
        $user->role           = 'Student';
        $user->account_status = 'Pending';
        $user->save();

        // Assign Spatie role
        $user->assignRole('student');

        return redirect()
            ->route('login.index')
            ->with('success', 'Registration submitted! Please wait for admin approval. You will receive login credentials via email once approved.');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        /**
         * SUPER ADMIN LOGIN
         * Use fullname + default password
         */
        $superAdmin = RegisteredUsers::where('fullname', $request->username)
            ->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))
            ->first();

        if ($superAdmin && Hash::check($request->password, $superAdmin->password)) {
            Auth::login($superAdmin, $request->has('remember'));
            $request->session()->regenerate();

            return redirect()->route('dashboard.index')
                ->with('success', 'Welcome Super Admin!');
        }

        /**
         * FACULTY LOGIN
         * Use faculty_no + password
         */
        $faculty = RegisteredUsers::where('faculty_no', $request->username)
            ->whereHas('roles', fn($q) => $q->where('name', 'Faculty'))
            ->first();

        if ($faculty) {
            if ($faculty->account_status !== 'Approved') {
                return back()->with('error', 'Your faculty account is not yet approved.');
            }

            if (!Hash::check($request->password, $faculty->password)) {
                return back()->with('error', 'Invalid faculty password.');
            }

            Auth::login($faculty, $request->has('remember'));
            $request->session()->regenerate();

            // Check if first login
            if ($faculty->first_login) {
                session(['force_password_change' => true]);
            }

            // return redirect()->route('faculty.dashboard')
            //     ->with('success', 'Welcome Faculty!')
            // 
                return redirect()->route('dashboard.index')
                ->with('success', 'Welcome Faculty!');;


        }

        /**
         * STUDENT LOGIN
         * Use student_no + password
         */
        $student = RegisteredUsers::where('student_no', $request->username)
            ->whereHas('roles', fn($q) => $q->where('name', 'Student'))
            ->first();

        if ($student) {
            if ($student->account_status !== 'Approved') {
                return back()->with('error', 'Your student account is not yet approved.');
            }

            if (!Hash::check($request->password, $student->password)) {
                return back()->with('error', 'Invalid student password.');
            }

            Auth::login($student, $request->has('remember'));
            $request->session()->regenerate();

             // Check if first login
            if ($student->first_login) {
                session(['force_password_change' => true]);
            }

            return redirect()->route('dashboard.index')
                ->with('success', 'Welcome Student!');
        }

        return back()->with('error', 'No matching account found.');
    }

    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->first_login = false; // mark as changed
        $user->save();

        session()->forget('force_password_change');

        return redirect()->route('dashboard.index')->with('success', 'Password updated successfully!');
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
