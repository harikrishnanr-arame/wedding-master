<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UserTemplate;
use App\Models\UserTemplateContent;
use Illuminate\Support\Facades\Storage;

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
    public function createTemplate($id)
    {
        // 1. Fetch the master template first!
        $template = \App\Models\Template::findOrFail($id);

        // 2. Check if the user already has a version of this (Optional but recommended)
        $existing = UserTemplate::where('user_id', auth()->id())
                                ->where('template_id', $id)
                                ->first();
        
        if ($existing) {
            return redirect()->route('template.edit', $existing->id);
        }

        // 3. Now $template->html_file will work perfectly
        $userTemplate = UserTemplate::create([
            'user_id' => auth()->id(),
            'template_id' => $id,
            'title' => 'My Wedding Website',
            'html_file' => $template->html_file
        ]);

        // 4. Define your default content...
        $defaultContent = [
            'couple_name' => 'Romeo & Juliet',
            'primary_color' => '#d63384',
        ];

        // 5. Save the initial content
        \App\Models\UserTemplateContent::create([
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

    //logic which work saving 
// public function saveTemplate(Request $request, $id)
// {
//     $content = json_decode($request->input('content'), true) ?? [];

//     if ($request->hasFile('gallery_images')) {

//         $galleryPaths = [];

//         foreach ($request->file('gallery_images') as $file) {

//             $path = $file->store('templates/gallery', 'public');

//             $galleryPaths[] = $path;
//         }

//         $content['gallery'] = $galleryPaths;
//     }

//     UserTemplateContent::updateOrCreate(
//         ['user_template_id' => $id],
//         ['content_json' => json_encode($content)]
//     );

//     return response()->json([
//         'success' => true
//     ]);
// }
   
   /**
     * Save or update wedding template content for the authenticated user.
     *
     * This method handles
     * 1. Authorization check (ensures template belongs to logged-in user)
     * 2. Loading existing saved content
     * 3. Merging incoming content with existing content
     * 4. Handling gallery image uploads
     * 5. Filtering invalid Base64 image strings
     * 6. Saving updated JSON content to database
     * 7. Optional publishing of the wedding website
     *
     * Content Handling Logic
     * - Existing content is preserved.
     * - Incoming content overwrites matching keys.
     * - Gallery images are merged separately.
     * - Newly uploaded images are stored in /storage/templates/gallery.
     *
     * Publishing Logic
     * If "publish" flag is true:
     * - Generates a unique public route.
     * - Creates or updates PublishedTemplate record.
     * - Returns public URL in response.
   */  
   public function saveTemplate(Request $request, $id)
   {
       try {
           $userTemplate = UserTemplate::where('id', $id)
               ->where('user_id', auth()->id())
               ->firstOrFail();

           $contentModel = UserTemplateContent::firstOrNew(['user_template_id' => $userTemplate->id]);
           
           // Ensure current content is an array
           $currentContent = json_decode($contentModel->content_json, true);
           if (!is_array($currentContent)) $currentContent = [];

           // Decode incoming content safely
           $incomingContent = $request->input('content');
           if (is_string($incomingContent)) {
               $incomingContent = json_decode($incomingContent, true);
           }
           if (!is_array($incomingContent)) $incomingContent = [];

           // 1. Handle Gallery Uploads
           $newGalleryPaths = [];
           if ($request->hasFile('gallery_images')) {
               foreach ($request->file('gallery_images') as $file) {
                   $newGalleryPaths[] = $file->store('templates/gallery', 'public');
               }
           }

           // 2. Merge General Fields
           // We use array_replace to ensure incoming values overwrite old ones
           $finalContent = array_replace_recursive($currentContent, $incomingContent);

           // 3. Handle Gallery Specifically
           $existingGalleryPaths = $incomingContent['gallery'] ?? [];
           $existingGalleryPaths = array_filter($existingGalleryPaths, function($path) {
               return is_string($path) && strpos($path, 'data:') === false;
           });

           $finalContent['gallery'] = array_merge($existingGalleryPaths, $newGalleryPaths);

           // 4. Save to Database
           $contentModel->content_json = json_encode($finalContent);
           $contentModel->save();

           // 5. Handle Publish Logic
           $publishUrl = null;
           if ($request->input('publish') == "1" || $request->input('publish') === true) {
               $route = 'wedding-' . $userTemplate->id . '-' . time();
                
               \App\Models\PublishedTemplate::updateOrCreate(
                   ['user_id' => auth()->id(), 'template_id' => $userTemplate->template_id],
                   [
                       'route' => $route,
                       'content_json' => json_encode($finalContent)
                   ]
               );
               $publishUrl = url('/published/' . $route);
           }

           return response()->json([
               'success' => true,
               'publish_url' => $publishUrl,
               'content' => $finalContent 
           ]);

       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'error' => $e->getMessage(),
               'file' => $e->getFile(),
               'line' => $e->getLine()
           ], 500);
       }
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

    /**
     * Display a publicly published wedding website.
     *
     * This method is responsible for rendering the live wedding website
     * using a unique route generated during the publish process.
     *
     * 1. Find the published template using the unique route.
     * 2. Retrieve the corresponding master template.
     * 3. Decode the stored JSON content.
     * 4. Pass the content and template HTML path to the public viewer.
     */
    public function viewPublishedSite($route)
    {
        $published = \App\Models\PublishedTemplate::where('route', $route)->firstOrFail();
        $masterTemplate = \App\Models\Template::findOrFail($published->template_id);

        return view('public.template_viewer', [
            'content' => json_decode($published->content_json, true),
            'templatePath' => $masterTemplate->html_file
        ]);
    }

}