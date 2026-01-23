<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * RegisterController handles user registration.
 *
 * This controller manages registration-related operations, including displaying the registration form
 * and processing new user registrations.
 */
class RegisterController extends Controller {
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showForm() {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     *
     * Validates input data, creates a new user, and redirects to login with success message.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        //Validate input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|digits_between:10,15',
            'password' => 'required|min:6|confirmed',
        ]);

        //Create user
        User::create([
            'user_name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        //Redirect after login
        return redirect()->route('login')
            ->with('success', 'Registration successful!');
    }
}
