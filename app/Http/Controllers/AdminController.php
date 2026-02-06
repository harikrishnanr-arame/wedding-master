<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;

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
    public function content()
    {
        $templates = Template::latest()->get();

        return view('admin.manage-content', compact('templates'));
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

    /**
     * Store a new website template.
     * - Validate incoming template data
     * - Upload cover image, HTML file, and CSS file to public storage
     * - Save template metadata in the database
     * Storage Location:
     * - Cover images → storage/app/public/templates/covers
     * - HTML files   → storage/app/public/templates/html
     * - CSS files    → storage/app/public/templates/css
     *
     * - Redirects back with success message on completion
     * - Dumps error message if an exception occurs
     */
    public function storeTemplate(Request $request) {

        try {

            $request->validate([
                'name' => 'required',
                'category' => 'required',
                'cover_image' => 'required|image',
                'html_file' => 'required|file',
                'css_file' => 'required|file',
            ]);

            $coverPath = $request->file('cover_image')->store('templates/covers', 'public');
            $htmlPath = $request->file('html_file')->store('templates/html', 'public');
            $cssPath = $request->file('css_file')->store('templates/css', 'public');

            Template::create([
                'name' => $request->name,
                'category' => $request->category,
                'cover_image' => $coverPath,
                'html_file' => $htmlPath,
                'css_file' => $cssPath,
                'is_active' => 1,
            ]);

            return redirect()->back()->with('success', 'Template added successfully!');

        } catch (\Exception $e) {

            dd($e->getMessage());
        }
    }

    /**
     * Delete a template by ID.
     *
     * - Find template by ID
     * - Remove associated files from public storage
     *   (cover image, HTML file, CSS file)
     * - Delete template record from database
     *
     * - Checks if file exists before deleting
     * - Prevents orphaned files in storage
     *
     * - Redirects back with success message
     */
    public function deleteTemplate($id) {

        $template = Template::findOrFail($id);

        // Delete files from storage
        if ($template->cover_image && Storage::disk('public')->exists($template->cover_image)) {
            Storage::disk('public')->delete($template->cover_image);
        }

        if ($template->html_file && Storage::disk('public')->exists($template->html_file)) {
            Storage::disk('public')->delete($template->html_file);
        }

        if ($template->css_file && Storage::disk('public')->exists($template->css_file)) {
            Storage::disk('public')->delete($template->css_file);
        }

        // Delete database record
        $template->delete();

        return redirect()->back()->with('success', 'Template deleted successfully!');
    }

    /**
     * Toggle template active status.
     *
     * Responsibilities:
     * - Find template by ID
     * - Switch is_active value (1 → 0 or 0 → 1)
     * - Save updated status
     *
     * @param int $id Template ID
     * @return RedirectResponse
     */
    public function toggleTemplate($id) {

        $template = Template::findOrFail($id);

        $template->is_active = !$template->is_active;
        $template->save();

        return redirect()->back()->with('success', 'Template status updated successfully!');
    }

}
