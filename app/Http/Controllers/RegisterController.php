<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // For Show register page
    public function showForm()
    {
        return view('auth.register');
    }

    //For Handle registration
    public function store(Request $request)
    {
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
