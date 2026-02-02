<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

/**
 * Class GoogleAuthController
 *
 * Handles Google OAuth authentication using Laravel Socialite.
 *
 * Responsibilities:
 * - Redirect user to Google login page
 * - Handle Google callback response
 * - Create or update user record
 * - Log the user into the application
 */
class GoogleAuthController extends Controller {
    
    /**
     * Redirect the user to Google's OAuth authentication page.
     */
    public function redirect() {

        try {

            return Socialite::driver('google')->redirect();

        } catch (Exception $e) {

            return redirect('/login')
                ->with('error', 'Unable to connect to Google. Please try again.');
        }
    }

    /**
     * Handle Google OAuth callback.
     *   
     * Retrieves user information from Google
     * Checks if the user already exists in the database
     * Creates a new user if not found
     * Updates existing user with Google ID if missing
     * Logs the user into the application
     */
    public function callback() {

        try {

            $googleUser = Socialite::driver('google')->user();

            // First try to find by google_id
            $user = User::where('google_id', $googleUser->id)->first();

            // If not found, find by email or create
            if (!$user) {
                $user = User::firstOrCreate(
                    ['email' => $googleUser->email],
                    [
                        'user_name' => $googleUser->name,
                        'google_id' => $googleUser->id,
                        'password'  => null,
                        'role'      => 'user',
                    ]
                );

                // If user exists but google_id is null, update it
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id
                    ]);
                }
            }

            Auth::login($user, true);

            return redirect('/')->with('success', 'Logged in with Google');

        } catch (Exception $e) {

            return redirect('/login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }
}
