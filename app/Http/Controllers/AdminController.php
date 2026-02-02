<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

/**
 * Class AdminController
 *
 * Handles all admin panel related pages.
 * Each method returns a specific admin view.
 */
class AdminController extends Controller {
    
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function users() {
        return view('admin.users');
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
}
