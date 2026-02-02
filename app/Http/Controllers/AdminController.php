<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {

    public function dashboard() {

        return view('admin.dashboard');
    }

    public function users() {

        return view('admin.users');
    }

    // Fetch users from database
    public function getUsers() {

        $users = User::latest()->get();
        return response()->json($users);
    }

    // Delete user
    public function deleteUser($id) {

        $user = User::findOrFail($id);

        // Prevent deleting admin
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Cannot delete admin'], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function content() {

        return view('admin.manage-content');
    }

    public function payments() {

        return view('admin.payments');
    }

    public function settings() {

        return view('admin.settings');
    }
    
    public function storeUser(Request $request) {

        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $roleValue = $request->role == 1 ? 'admin' : 'user';

        User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $roleValue
        ]);

        return response()->json(['success' => true]);
    }

}


