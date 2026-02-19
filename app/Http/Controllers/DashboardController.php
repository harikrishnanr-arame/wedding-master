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

        UserTemplateContent::create([
            'user_template_id' => $userTemplate->id,
            'content_json' => json_encode([])
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
        $userTemplate = UserTemplate::where('user_id', auth()->id())
            ->with(['content', 'template'])
            ->findOrFail($id);

        $existing = $userTemplate->content ? json_decode($userTemplate->content->content_json, true) : [];
        $content = array_merge($existing, $request->input('content', []));

        $fields = $userTemplate->template->fields ?? [];

        foreach($fields as $field){
            if($field['type'] === 'image' && $request->hasFile($field['name'])){
                $content[$field['name']] = $request->file($field['name'])->store('templates', 'public');
            }
        }

        // Handle gallery uploads
        foreach($fields as $field){
            if($field['type'] === 'gallery' && $request->hasFile($field['name'])){
                foreach($request->file($field['name']) as $image){
                    $path = $image->store('galleries','public');
                    Gallery::create([
                        'user_template_id' => $userTemplate->id,
                        'image_path' => $path
                    ]);
                }
            }
        }

        $userTemplate->content()->updateOrCreate(
            ['user_template_id' => $id],
            ['content_json' => json_encode($content)]
        );

        return response()->json(['success'=>true]);
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
