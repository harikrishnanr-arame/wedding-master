<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

    public function login(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();

            if (auth()->user()->isAdmin()) {
                return redirect('/admin/dashboard')
                    ->with('success', 'Welcome Admin');
            }

            return redirect('/')
                ->with('success', 'Logged in successfully');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ]);
    }

}
