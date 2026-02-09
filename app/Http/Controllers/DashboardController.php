<?php

namespace App\Http\Controllers;

class DashboardController extends Controller {

    public function profile() {

        return view('dashboard.profile');
    }

    public function templates() {

        return view('dashboard.templates');
    }

    public function payments() {
        
        return view('dashboard.payments');
    }
}
