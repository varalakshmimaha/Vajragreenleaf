<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionType;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $pages = Page::with('sections')->orderBy('order')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'layout' => 'in:default,full-width,sidebar',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:20480',
            'is_active' => 'boolean',
            'is_homepage' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->boolean('is_homepage')) {
            Page::query()->update(['is_homepage' => false]);
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->fileUploadService->upload($request->file('og_image'), 'pages');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['order'] = Page::max('order') + 1;

        $page = Page::create($data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $page->load('sections.sectionType');
        return view('admin.pages.form', compact('page'));
    }

    public function builder(Page $page)
    {
        $page->load('sections.sectionType');
        $sectionTypes = SectionType::active()->get();
        return view('admin.pages.builder', compact('page', 'sectionTypes'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'layout' => 'in:default,full-width,sidebar',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:20480',
            'is_active' => 'boolean',
            'is_homepage' => 'boolean',
        ]);

        if ($request->boolean('is_homepage') && !$page->is_homepage) {
            Page::query()->update(['is_homepage' => false]);
        }

        if ($request->hasFile('og_image')) {
            $this->fileUploadService->delete($page->og_image);
            $data['og_image'] = $this->fileUploadService->upload($request->file('og_image'), 'pages');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_homepage'] = $request->boolean('is_homepage');

        $page->update($data);

        return back()->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if ($page->og_image) {
            $this->fileUploadService->delete($page->og_image);
        }
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function storeSection(Request $request, Page $page)
    {
        $data = $request->validate([
            'section_type_id' => 'required|exists:section_types,id',
            'order' => 'nullable|integer',
        ]);

        $data['order'] = $data['order'] ?? ($page->sections()->max('order') + 1);
        $data['is_active'] = true;

        $page->sections()->create($data);

        return back()->with('success', 'Section added successfully.');
    }

    public function updateSection(Request $request, Page $page, PageSection $section)
    {
        $data = $request->validate([
            'settings' => 'nullable|array',
            'settings.image' => 'nullable|image|max:20480',
            'order' => 'nullable|integer',
        ]);

        if ($request->has('order')) {
            $data['order'] = $request->order;
        }

        // Handle image upload for about section
        $settings = $request->input('settings', []);
        if ($request->hasFile('settings.image')) {
            // Delete old image if exists
            $oldImage = $section->settings['image'] ?? null;
            if ($oldImage) {
                $this->fileUploadService->delete($oldImage);
            }
            $settings['image'] = $this->fileUploadService->upload($request->file('settings.image'), 'sections/about');
        } elseif (isset($section->settings['image']) && !$request->has('settings.remove_image')) {
            // Keep existing image if not uploading new one and not removing
            $settings['image'] = $section->settings['image'];
        }

        // Handle remove image checkbox
        if ($request->has('settings.remove_image') && $request->input('settings.remove_image')) {
            $oldImage = $section->settings['image'] ?? null;
            if ($oldImage) {
                $this->fileUploadService->delete($oldImage);
            }
            unset($settings['image']);
        }

        // Handle show_button checkbox (unchecked = not sent)
        $settings['show_button'] = $request->has('settings.show_button') ? true : false;

        // Handle features (convert comma-separated string to array)
        if (isset($settings['features']) && is_string($settings['features'])) {
            $features = array_map('trim', explode(',', $settings['features']));
            $settings['features'] = array_filter($features); // Remove empty values
        }

        $data['settings'] = $settings;

        $section->update($data);

        return back()->with('success', 'Section updated successfully.');
    }

    public function destroySection(Page $page, PageSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section deleted successfully.');
    }

    public function toggleSection(Page $page, PageSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);
        return back()->with('success', 'Section status updated.');
    }

    public function reorderSections(Request $request, Page $page)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'exists:page_sections,id',
        ]);

        foreach ($request->sections as $index => $sectionId) {
            PageSection::where('id', $sectionId)
                ->where('page_id', $page->id)
                ->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
