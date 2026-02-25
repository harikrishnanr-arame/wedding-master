<?php
namespace App\Http\Controllers;

/**
 * Class DashboardController
 *
 * Handles user dashboard related pages.
 *
 * - Display authenticated user's profile page
 * - Display user's templates page
 * - Display user's payments page
 *
 * All routes using this controller should be protected
 * by authentication middleware to ensure only logged-in
 * users can access dashboard features.
 */
class DashboardController extends Controller
{
    /** Display authenticated user's profile page */
    public function profile()
    {
        return view('dashboard.profile');
    }

    /** Display user's templates page */
    public function templates()
    {
        return view('dashboard.templates');
    }

    /** Display user's payments page */
    public function payments()
    {
        return view('dashboard.payments');
    }
}
