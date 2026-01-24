<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * LoginController handles user authentication.
 *
 * This controller manages login-related operations, including displaying the login form
 * and processing login attempts.
 */
class LoginController extends Controller {

    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin() {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            Log::channel('custom_log')->error('Error in LoginController@showLogin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => request()->all()
            ]);
            return response()->view('errors.500', [], 500);
        } finally {
            Log::channel('custom_log')->info('LoginController@showLogin method executed');
        }
    }

    /**
     * Handle user login attempt.
     *
     * Validates the provided credentials and logs in the user if successful.
     * Regenerates the session for security and redirects to home with success message.
     * If login fails, redirects back with error message.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect('/')->with('success', 'Logged in successfully');
            }

            return back()->withErrors([
                'email' => 'Invalid email or password',
            ]);
        } catch (\Exception $e) {
            Log::channel('custom_log')->error('Error in LoginController@login: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['email' => 'An error occurred. Please try again.']);
        } finally {
            Log::channel('custom_log')->info('LoginController@login method executed');
        }
    }
}
