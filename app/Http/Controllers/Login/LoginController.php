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
        $user->role           = 'student';
        $user->account_status = 'pending';
        $user->save();

        return redirect()
            ->route('login.index')
            ->with('success', 'Registration submitted! Please wait for admin approval. You will receive login credentials via email once approved.');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'student_no' => 'required|string',
            'password'   => 'required|string',
        ]);

        // Find user by student number
        $user = RegisteredUsers::where('student_no', $request->student_no)->first();

        if (!$user) {
            return back()->with('error', 'Student number not found.');
        }

        // Check if approved
        if ($user->account_status !== 'approved') {
            return back()->with('error', 'Your account is not yet approved.');
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid password.');
        }

            Auth::login($user, $request->has('remember'));

            // Regenerate session for security
            $request->session()->regenerate();

        return redirect()->route('dashboard.index');
    }

}
