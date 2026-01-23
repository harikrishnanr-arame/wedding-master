<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

/**
 * HomeController handles requests for the home page.
 *
 * This controller is responsible for displaying the main landing page of the application.
 */
class HomeController extends Controller {
    /**
     * Display the home page view.
     *
     * @return \Illuminate\View\View The welcome view
     */
    public function home() {
        return view('welcome');
    }
}

