<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublishedTemplate;

class PublishedTemplateController extends Controller
{
    /**
     * Show the published template.
     *
     * @param string $route
     * @return \Illuminate\View\View
     */
    public function show($route)
    {
        // Find the published template by route
        $published = PublishedTemplate::where('route', $route)->firstOrFail();

        // Decode the content
        $content = json_decode($published->content_json, true);

        // Get the template HTML file path
        $htmlFile = $published->template->html_file;

        // Pass data to a simple view that loads the HTML
        return view('published.template', compact('htmlFile', 'content'));
    }
}