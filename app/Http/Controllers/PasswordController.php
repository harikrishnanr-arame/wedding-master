<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Handles password reset functionality.
 *
 * Show forgot password form
 * Send reset link to email
 * Show reset password form
 * Update user password
 */
class PasswordController extends Controller {

    /**
     * Display forgot password form.
     */
    public function forgotForm() {

        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to the user's email.
     */
    public function sendLink(Request $request) {
         
        // Validate email exists in users table
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent to your email')
            : back()->withErrors(['email' => 'Unable to send reset link']);
    }

    /**
     * Show reset password form.
     */
    public function resetForm(Request $request, $token) {

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset submission.
     *
     * Validate token and user credentials
     * Update password securely
     * Regenerate remember token
     */
    public function reset(Request $request) {
        
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {

                // Update hashed password
                $user->password = Hash::make($password);

                // Optional: track password change time
                $user->password_changed_at = now();

                // Invalidate old remember tokens
                $user->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')
                ->with('success', 'Password reset successful')
            : back()->withErrors(['email' => 'Reset failed']);
    }
}
