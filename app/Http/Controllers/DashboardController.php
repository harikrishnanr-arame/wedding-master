<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTemplate;
use App\Models\UserTemplateContent;
use App\Models\Gallery;

class DashboardController extends Controller
{
    public function createTemplate($templateId)
    {
        $existing = UserTemplate::where('user_id', auth()->id())
            ->where('template_id', $templateId)
            ->first();

        if ($existing) return redirect()->route('template.edit', $existing->id);

        $userTemplate = UserTemplate::create([
            'user_id' => auth()->id(),
            'template_id' => $templateId,
            'title' => 'My Wedding Website'
        ]);

        // PRE-FILL with default "Visible" settings and placeholder items
        $defaultContent = [
            'show_countdown' => 1,
            'show_story' => 1,
            'show_events' => 1,
            'show_gallery' => 1,
            'couple_name' => 'Romeo & Juliet',
            'love_story' => [
                ['title' => 'The First Meeting', 'description' => 'It all started here...', 'image' => '']
            ],
            'events' => [
                ['title' => 'Ceremony', 'date' => 'Sept 24, 2026', 'location' => 'St. Peters']
            ]
        ];

        UserTemplateContent::create([
            'user_template_id' => $userTemplate->id,
            'content_json' => json_encode($defaultContent)
        ]);

        return redirect()->route('template.edit', $userTemplate->id);
    }

    public function editTemplate($id)
    {
        $userTemplate = UserTemplate::with(['template', 'content'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $fields = $userTemplate->template->fields ? json_decode($userTemplate->template->fields, true) : [];
        $content = $userTemplate->content ? json_decode($userTemplate->content->content_json, true) : [];
        $templatePath = $userTemplate->template->html_file;

        return view('dashboard.templateEditPage', compact('userTemplate','fields','content','templatePath'));
    }

    public function saveTemplate(Request $request, $id)
    {
        // 1. Find the template
        $userTemplate = UserTemplate::where('user_id', auth()->id())->findOrFail($id);

        // 2. Get the content (Try standard input first, then raw body)
        $content = $request->input('content');
        
        if (!$content) {
            $rawBody = json_decode($request->getContent(), true);
            $content = $rawBody['content'] ?? null;
        }

        // 3. Last resort check
        if (!$content) {
            return response()->json([
                'success' => false, 
                'message' => 'The server rejected the data. The file size might be too large for PHP settings.'
            ], 400);
        }

        // 4. Update the Database
        $userTemplate->content()->updateOrCreate(
            ['user_template_id' => $userTemplate->id],
            ['content_json' => json_encode($content)]
        );

        return response()->json(['success' => true]);
    }

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function payments()
    {
        return view('dashboard.payments');
    }

    public function templates()
    {
        $userTemplates = UserTemplate::where('user_id', auth()->id())
            ->with('template')
            ->latest()
            ->get();

        return view('dashboard.templates', compact('userTemplates'));
    }

}
