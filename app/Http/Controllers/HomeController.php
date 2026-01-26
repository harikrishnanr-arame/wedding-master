<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            return view('welcome');
        } catch (\Exception $e) {
            Log::channel('custom_log')->error('Error in HomeController@home: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => request()->all()
            ]);
            return response()->view('errors.500', [], 500);
        } finally {
            Log::channel('custom_log')->info('HomeController@home method executed');
        }
    }
}

