<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller {

    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {

        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            $user = User::create([
                'user_name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => null,
                'role' => 'user',
            ]);
        } else {
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            }
        }

        Auth::login($user, true);

        return redirect('/')->with('success', 'Logged in with Google');
    }
}