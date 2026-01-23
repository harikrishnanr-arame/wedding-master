<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller{

    public function forgotForm() {

        return view('auth.forgot-password');
    }

    public function sendLink(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent to your email')
            : back()->withErrors(['email' => 'Unable to send reset link']);
    }

    public function resetForm(Request $request, $token) {

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request) {
        
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->password_changed_at = now();
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful')
            : back()->withErrors(['email' => 'Reset failed']);
    }
}
