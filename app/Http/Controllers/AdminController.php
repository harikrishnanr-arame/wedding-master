<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminController
 *
 * Handles all admin dashboard operations.
 *
 * Responsibilities:
 * - Display admin dashboard pages
 * - Manage users (list, create, delete)
 * - Manage payments (list, delete)
 * - Provide AJAX endpoints for dynamic data loading
 */
class AdminController extends Controller {

    /**
     * Display the admin dashboard page.
     */
    public function dashboard() {

        return view('admin.dashboard');
    }

    /**
     * Display the users management page.
     */
    public function users() {

        return view('admin.users');
    }

    /**
     * Fetch all users
     *
     * Returns a JSON response containing
     * all users ordered by latest.
     */
    public function getUsers() {

        $users = User::latest()->get();
        return response()->json($users);
    }

    /**
     * Delete a user by ID.
     *
     * Prevents deletion of admin users.
     * Returns JSON success or error response.
     */
    public function deleteUser($id) {

        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return response()->json([
                'error' => 'Cannot delete admin'
            ], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Store a new user.
     *
     * Validates input data
     * Hashes password
     * Assigns role (admin/user)
     */
    public function storeUser(Request $request) {

        $request->validate([
            'user_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => 'required'
        ]);

        $roleValue = $request->role == 1 ? 'admin' : 'user';

        User::create([
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $roleValue
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Display the content management page.
     */
    public function content() {

        return view('admin.manage-content');
    }

    /**
     * Display the admin settings page.
     */
    public function settings() {

        return view('admin.settings');
    }

    /**
     * Display the payments management page.
     *
     * Retrieves:
     * - All payments with user relation
     * - Total revenue (paid)
     * - Total pending payments
     * - Total failed payments
     */
    public function payments() {

        $payments = Payment::with('user')->latest()->get();

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $totalPending = Payment::where('status', 'pending')->sum('amount');
        $totalFailed  = Payment::where('status', 'failed')->sum('amount');

        return view('admin.payments', compact(
            'payments',
            'totalRevenue',
            'totalPending',
            'totalFailed'
        ));
    }

    /**
     * Fetch all payments
     *
     * Returns payments with related user data.
     */
    public function getPayments() {

        $payments = Payment::with('user')->latest()->get();
        return response()->json($payments);
    }

    /**
     * Delete a payment by ID
     *
     * Returns JSON success response.
     */
    public function deletePayment($id) {

        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['success' => true]);
    }
}
