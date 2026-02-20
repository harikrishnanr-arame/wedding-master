<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTemplate;
use App\Models\UserTemplateContent;
use App\Models\Gallery;

/**
 * Class DashboardController
 *
 * Handles all dashboard-related functionality including:
 * - Creating wedding templates
 * - Editing templates
 * - Saving template content
 * - Viewing profile and payments
 * - Listing user templates
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Create a new template instance for the authenticated user.
     *
     * If the user already created this template, redirect to edit page.
     * Otherwise:
     * - Create a new UserTemplate record
     * - Pre-fill it with default placeholder content
     * - Redirect to edit page
     *
     * @param int $templateId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTemplate($templateId)
    {
        $existing = UserTemplate::where('user_id', auth()->id())
            ->where('template_id', $templateId)
            ->first();

        if ($existing) {
            return redirect()->route('template.edit', $existing->id);
        }

        $userTemplate = UserTemplate::create([
            'user_id' => auth()->id(),
            'template_id' => $templateId,
            'title' => 'My Wedding Website'
        ]);

        // Default template content structure
        $defaultContent = [
            'show_countdown' => 1,
            'show_story' => 1,
            'show_events' => 1,
            'show_gallery' => 1,
            'couple_name' => 'Romeo & Juliet',
            'love_story' => [
                [
                    'title' => 'The First Meeting',
                    'description' => 'It all started here...',
                    'image' => ''
                ]
            ],
            'events' => [
                [
                    'title' => 'Ceremony',
                    'date' => 'Sept 24, 2026',
                    'location' => 'St. Peters'
                ]
            ]
        ];

        UserTemplateContent::create([
            'user_template_id' => $userTemplate->id,
            'content_json' => json_encode($defaultContent)
        ]);

        return redirect()->route('template.edit', $userTemplate->id);
    }

    /**
     * Display the template editor page.
     *
     * Loads:
     * - Template structure (fields)
     * - Existing user content
     * - Template HTML file path
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editTemplate($id)
    {
        $userTemplate = UserTemplate::with(['template', 'content'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $fields = $userTemplate->template->fields
            ? json_decode($userTemplate->template->fields, true)
            : [];

        $content = $userTemplate->content
            ? json_decode($userTemplate->content->content_json, true)
            : [];

        $templatePath = $userTemplate->template->html_file;

        return view(
            'dashboard.templateEditPage',
            compact('userTemplate', 'fields', 'content', 'templatePath')
        );
    }

    /**
     * Save template content updates from the editor.
     *
     * Accepts JSON data via:
     * - Standard form input
     * - Raw JSON request body (fallback)
     *
     * Updates or creates the related UserTemplateContent record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTemplate(Request $request, $id)
    {
        $userTemplate = UserTemplate::where('user_id', auth()->id())
            ->findOrFail($id);

        // Attempt to retrieve content from input
        $content = $request->input('content');

        // Fallback: try raw JSON body
        if (!$content) {
            $rawBody = json_decode($request->getContent(), true);
            $content = $rawBody['content'] ?? null;
        }

        // Final validation check
        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'The server rejected the data. The file size might be too large for PHP settings.'
            ], 400);
        }

        // Save or update content
        $userTemplate->content()->updateOrCreate(
            ['user_template_id' => $userTemplate->id],
            ['content_json' => json_encode($content)]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Show user profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('dashboard.profile');
    }

    /**
     * Show user payments page.
     *
     * @return \Illuminate\View\View
     */
    public function payments()
    {
        return view('dashboard.payments');
    }

    /**
     * Display all templates created by the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function templates()
    {
        $userTemplates = UserTemplate::where('user_id', auth()->id())
            ->with('template')
            ->latest()
            ->get();

        return view('dashboard.templates', compact('userTemplates'));
    }
}