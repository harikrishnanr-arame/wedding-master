<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Template;

class HomeController extends Controller
{
    /**
     * Display the home page with active templates.
     */
    public function home()
    {
        try {

            // Fetch only active templates ordered by latest
            $templates = Template::where('is_active', 1)
                                ->latest()
                                ->get();

            return view('welcome', compact('templates'));

        } catch (\Exception $e) {

            // Log the error
            Log::channel('custom_log')->error(
                'Error in HomeController@home: ' . $e->getMessage(),
                [
                    'trace' => $e->getTraceAsString(),
                    'request_data' => request()->all()
                ]
            );

            return response()->view('errors.500', [], 500);

        } finally {

            Log::channel('custom_log')->info('HomeController@home method executed');
        }
    }
}
