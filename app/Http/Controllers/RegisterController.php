<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        try {
            return view('auth.register');
        } catch (\Exception $e) {
            Log::channel('custom_log')->error('Error in RegisterController@showForm: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => request()->all()
            ]);
            return response()->view('errors.500', [], 500);
        } finally {
            Log::channel('custom_log')->info('RegisterController@showForm method executed');
        }
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
        
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|digits_between:10,15|unique:users,mobile_number',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This mobile number is already registered.',
        ]);

        // Create user
        User::create([
            'user_name' => $validated['name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful!');
    }

}
