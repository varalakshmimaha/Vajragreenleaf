<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionType;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    protected $fileUpload;

    public function __construct(FileUploadService $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    public function index()
    {
        $sections = Section::withCount('pages')->ordered()->paginate(20);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $layouts = Section::getLayoutOptions();
        $animations = Section::getAnimationOptions();
        $cardStyles = Section::getCardStyles();
        $hoverEffects = Section::getHoverEffects();

        return view('admin.sections.form', compact('layouts', 'animations', 'cardStyles', 'hoverEffects'));
    }

    public function store(Request $request)
    {
        $data = $this->validateSection($request);

        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();

        // Handle file uploads
        $data = $this->handleFileUploads($request, $data);

        // Handle items with image uploads
        if ($request->has('items')) {
            $data['items'] = $this->processItemsWithImages($request);
        }

        // Handle gallery
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->fileUpload->upload($image, 'sections/gallery');
            }
            $data['gallery'] = $gallery;
        }

        Section::create($data);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $layouts = Section::getLayoutOptions();
        $animations = Section::getAnimationOptions();
        $cardStyles = Section::getCardStyles();
        $hoverEffects = Section::getHoverEffects();

        return view('admin.sections.form', compact('section', 'layouts', 'animations', 'cardStyles', 'hoverEffects'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $this->validateSection($request);

        // Handle file uploads
        $data = $this->handleFileUploads($request, $data, $section);

        // Handle items with image uploads
        if ($request->has('items')) {
            $data['items'] = $this->processItemsWithImages($request, $section);
        }

        // Handle gallery
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($section->gallery) {
                foreach ($section->gallery as $image) {
                    $this->fileUpload->delete($image);
                }
            }
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->fileUpload->upload($image, 'sections/gallery');
            }
            $data['gallery'] = $gallery;
        }

        // Handle gallery image deletion
        if ($request->has('delete_gallery_images')) {
            $currentGallery = $section->gallery ?? [];
            $toDelete = $request->input('delete_gallery_images', []);
            foreach ($toDelete as $index) {
                if (isset($currentGallery[$index])) {
                    $this->fileUpload->delete($currentGallery[$index]);
                    unset($currentGallery[$index]);
                }
            }
            $data['gallery'] = array_values($currentGallery);
        }

        $section->update($data);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        // Delete associated files
        if ($section->image) {
            $this->fileUpload->delete($section->image);
        }
        if ($section->background_image) {
            $this->fileUpload->delete($section->background_image);
        }
        if ($section->gallery) {
            foreach ($section->gallery as $image) {
                $this->fileUpload->delete($image);
            }
        }

        // Detach from all pages
        $section->pages()->detach();

        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }

    public function duplicate(Section $section)
    {
        $newSection = $section->replicate();
        $newSection->name = $section->name . ' (Copy)';
        $newSection->slug = Str::slug($newSection->name) . '-' . uniqid();
        $newSection->save();

        return redirect()->route('admin.sections.edit', $newSection)
            ->with('success', 'Section duplicated successfully.');
    }

    public function preview(Section $section)
    {
        return view('admin.sections.preview', compact('section'));
    }

    // Page Section Management
    public function pageBuilder(Page $page)
    {
        $page->load(['pageSections.section', 'pageSections.sectionType']);
        $availableSections = Section::active()->reusable()->ordered()->get();
        $sectionTypes = SectionType::where('is_active', true)->get();

        return view('admin.sections.page-builder', compact('page', 'availableSections', 'sectionTypes'));
    }

    public function addToPage(Request $request, Page $page)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);

        $maxOrder = $page->pageSections()->max('order') ?? 0;

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $request->section_id,
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Section added to page.');
    }

    // Add old section type to page
    public function addSectionType(Request $request, Page $page)
    {
        $request->validate([
            'section_type_id' => 'required|exists:section_types,id',
        ]);

        $maxOrder = $page->pageSections()->max('order') ?? 0;

        PageSection::create([
            'page_id' => $page->id,
            'section_type_id' => $request->section_type_id,
            'order' => $maxOrder + 1,
            'is_active' => true,
            'content' => [],
        ]);

        return back()->with('success', 'Section type added to page.');
    }

    public function removeFromPage(Page $page, PageSection $pageSection)
    {
        if ($pageSection->page_id !== $page->id) {
            abort(403);
        }

        $pageSection->delete();

        return back()->with('success', 'Section removed from page.');
    }

    public function togglePageSection(PageSection $pageSection)
    {
        $pageSection->update(['is_active' => !$pageSection->is_active]);

        return response()->json(['success' => true, 'is_active' => $pageSection->is_active]);
    }

    public function reorderPageSections(Request $request, Page $page)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.order' => 'required|integer',
        ]);

        foreach ($request->sections as $item) {
            PageSection::where('id', $item['id'])
                ->where('page_id', $page->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function updatePageSectionOverrides(Request $request, PageSection $pageSection)
    {
        $overrides = $request->input('overrides', []);
        $pageSection->update(['overrides' => $overrides]);

        return response()->json(['success' => true]);
    }

    // Get page section for editing (API)
    public function getPageSection(PageSection $pageSection)
    {
        $pageSection->load(['section', 'sectionType']);

        return response()->json([
            'id' => $pageSection->id,
            'title' => $pageSection->title,
            'content' => $pageSection->content,
            'settings' => $pageSection->settings,
            'is_active' => $pageSection->is_active,
            'section_type' => $pageSection->sectionType ? [
                'id' => $pageSection->sectionType->id,
                'name' => $pageSection->sectionType->name,
                'slug' => $pageSection->sectionType->slug,
            ] : null,
            'section' => $pageSection->section ? [
                'id' => $pageSection->section->id,
                'name' => $pageSection->section->name,
            ] : null,
        ]);
    }

    // Update page section (API)
    public function updatePageSection(Request $request, PageSection $pageSection)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        // Merge content with title and subtitle
        $content = $data['content'] ?? $pageSection->content ?? [];
        if ($request->has('subtitle')) {
            $content['subtitle'] = $request->input('subtitle');
        }
        if ($request->has('limit')) {
            $content['limit'] = (int) $request->input('limit');
        }

        $pageSection->update([
            'title' => $data['title'] ?? $pageSection->title,
            'content' => $content,
            'settings' => $data['settings'] ?? $pageSection->settings,
        ]);

        return redirect()->back()->with('success', 'Section updated successfully.');
    }

    // Helper methods
    protected function validateSection(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
            'image_position' => 'nullable|string',
            'video_url' => 'nullable|url',
            'layout' => 'required|string',
            'columns' => 'nullable|string',
            'container_width' => 'nullable|string',
            'background_type' => 'nullable|string',
            'background_color' => 'nullable|string|max:50',
            'background_gradient' => 'nullable|string',
            'background_image' => 'nullable|image|max:20480',
            'background_overlay' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'title_size' => 'nullable|string',
            'title_alignment' => 'nullable|string',
            'content_alignment' => 'nullable|string',
            'padding_top' => 'nullable|string',
            'padding_bottom' => 'nullable|string',
            'margin_top' => 'nullable|string',
            'margin_bottom' => 'nullable|string',
            'animation_type' => 'nullable|string',
            'animation_delay' => 'nullable|string',
            'animation_duration' => 'nullable|string',
            'card_style' => 'nullable|string',
            'card_hover' => 'nullable|string',
            'card_rounded' => 'boolean',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'button_style' => 'nullable|string',
            'button_size' => 'nullable|string',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'custom_class' => 'nullable|string|max:255',
            'custom_id' => 'nullable|string|max:255',
            'use_theme_colors' => 'boolean',
            'order' => 'nullable|integer',
            'is_reusable' => 'boolean',
            'is_active' => 'boolean',
        ]);
    }

    protected function handleFileUploads(Request $request, array $data, ?Section $section = null): array
    {
        // Handle main image - either file upload or URL
        if ($request->hasFile('image')) {
            if ($section && $section->image) {
                $this->fileUpload->delete($section->image);
            }
            $data['image'] = $this->fileUpload->upload($request->file('image'), 'sections');
        } elseif ($request->input('image_source') === 'url' && $request->filled('image_url')) {
            // Download image from URL and store locally
            $imagePath = $this->fileUpload->uploadFromUrl($request->input('image_url'), 'sections');
            if ($imagePath) {
                if ($section && $section->image) {
                    $this->fileUpload->delete($section->image);
                }
                $data['image'] = $imagePath;
            }
        }

        // Handle background image - either file upload or URL
        if ($request->hasFile('background_image')) {
            if ($section && $section->background_image) {
                $this->fileUpload->delete($section->background_image);
            }
            $data['background_image'] = $this->fileUpload->upload($request->file('background_image'), 'sections/backgrounds');
        } elseif ($request->input('bg_image_source') === 'url' && $request->filled('background_image_url')) {
            // Download background image from URL and store locally
            $bgImagePath = $this->fileUpload->uploadFromUrl($request->input('background_image_url'), 'sections/backgrounds');
            if ($bgImagePath) {
                if ($section && $section->background_image) {
                    $this->fileUpload->delete($section->background_image);
                }
                $data['background_image'] = $bgImagePath;
            }
        }

        // Handle checkbox fields
        $data['card_rounded'] = $request->boolean('card_rounded', true);
        $data['use_theme_colors'] = $request->boolean('use_theme_colors', true);
        $data['is_reusable'] = $request->boolean('is_reusable', true);
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    protected function processItems($items): array
    {
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if (!is_array($items)) {
            return [];
        }

        return array_filter($items, function ($item) {
            return !empty($item['title']) || !empty($item['content']) || !empty($item['icon']);
        });
    }

    /**
     * Process items with image uploads
     */
    protected function processItemsWithImages(Request $request, ?Section $section = null): array
    {
        $items = $request->input('items', []);
        $itemImages = $request->file('item_images', []);
        $existingItems = $section ? ($section->items ?? []) : [];

        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if (!is_array($items)) {
            return [];
        }

        $processedItems = [];

        foreach ($items as $index => $item) {
            // Skip empty items
            if (empty($item['title']) && empty($item['content']) && empty($item['icon'])) {
                continue;
            }

            $processedItem = [
                'icon' => $item['icon'] ?? '',
                'title' => $item['title'] ?? '',
                'content' => $item['content'] ?? '',
                'url' => $item['url'] ?? '',
                'image' => '',
            ];

            // Check if there's a new image upload for this item
            if (isset($itemImages[$index]) && $itemImages[$index]->isValid()) {
                // Delete old image if exists
                if (!empty($item['existing_image'])) {
                    $this->fileUpload->delete($item['existing_image']);
                }
                // Upload new image
                $processedItem['image'] = $this->fileUpload->upload($itemImages[$index], 'sections/items');
            }
            // Check if we should keep the existing image
            elseif (!empty($item['existing_image']) && empty($item['delete_image'])) {
                $processedItem['image'] = $item['existing_image'];
            }
            // Check if image should be deleted
            elseif (!empty($item['delete_image']) && !empty($item['existing_image'])) {
                $this->fileUpload->delete($item['existing_image']);
            }

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }
}
