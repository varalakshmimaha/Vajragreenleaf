<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    protected $fileUpload;

    public function __construct(FileUploadService $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    public function index()
    {
        $galleries = Gallery::with('category')->ordered()->paginate(20);
        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.index', compact('galleries', 'categories'));
    }

    public function create()
    {
        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'tags' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUpload->upload($request->file('image'), 'gallery');
        }

        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        Gallery::create($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image added successfully.');
    }

    public function edit(Gallery $gallery)
    {
        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.form', compact('gallery', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'tags' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                $this->fileUpload->delete($gallery->image);
            }
            $data['image'] = $this->fileUpload->upload($request->file('image'), 'gallery');
        }

        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        } else {
            $data['tags'] = null;
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            $this->fileUpload->delete($gallery->image);
        }
        if ($gallery->thumbnail) {
            $this->fileUpload->delete($gallery->thumbnail);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image deleted successfully.');
    }

    // Categories
    public function categories()
    {
        $categories = GalleryCategory::withCount('galleries')->ordered()->get();
        return view('admin.gallery.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = $data['order'] ?? 0;

        GalleryCategory::create($data);

        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, GalleryCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        $category->update($data);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(GalleryCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
