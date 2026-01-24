<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $products = Product::orderBy('order')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'key_benefits' => 'nullable|string',
            'directions' => 'nullable|string',
            'actions_indications' => 'nullable|string',
            'method_of_use' => 'nullable|string',
            'dosage' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'features' => 'nullable|array',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|mimes:mp4,webm|max:51200',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'nullable|numeric|min:0',
            'price_label' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $this->fileUploadService->upload($request->file('main_image'), 'products');
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $this->fileUploadService->upload($request->file('video_file'), 'products/videos');
        }

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $this->fileUploadService->upload($request->file('pdf_file'), 'products/pdfs');
        }

        if ($request->hasFile('gallery')) {
            $data['gallery'] = $this->fileUploadService->uploadMultiple($request->file('gallery'), 'products/gallery');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Product::max('order') + 1;

        $product = Product::create($data);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'key_benefits' => 'nullable|string',
            'directions' => 'nullable|string',
            'actions_indications' => 'nullable|string',
            'method_of_use' => 'nullable|string',
            'dosage' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'features' => 'nullable|array',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|mimes:mp4,webm|max:51200',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'nullable|numeric|min:0',
            'price_label' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('main_image')) {
            $this->fileUploadService->delete($product->main_image);
            $data['main_image'] = $this->fileUploadService->upload($request->file('main_image'), 'products');
        }

        if ($request->hasFile('video_file')) {
            $this->fileUploadService->delete($product->video_file);
            $data['video_file'] = $this->fileUploadService->upload($request->file('video_file'), 'products/videos');
        }

        if ($request->hasFile('pdf_file')) {
            $this->fileUploadService->delete($product->pdf_file);
            $data['pdf_file'] = $this->fileUploadService->upload($request->file('pdf_file'), 'products/pdfs');
        }

        if ($request->hasFile('gallery')) {
            $this->fileUploadService->deleteMultiple($product->gallery ?? []);
            $data['gallery'] = $this->fileUploadService->uploadMultiple($request->file('gallery'), 'products/gallery');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->fileUploadService->delete($product->main_image);
        $this->fileUploadService->delete($product->video_file);
        $this->fileUploadService->delete($product->pdf_file);
        $this->fileUploadService->deleteMultiple($product->gallery ?? []);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function deleteGalleryImage(Request $request, Product $product)
    {
        $index = $request->input('index');
        $gallery = $product->gallery ?? [];

        if (isset($gallery[$index])) {
            $this->fileUploadService->delete($gallery[$index]);
            unset($gallery[$index]);
            $product->update(['gallery' => array_values($gallery)]);
        }

        return back()->with('success', 'Image deleted successfully.');
    }
}
