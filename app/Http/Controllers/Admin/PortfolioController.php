<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $portfolios = Portfolio::with('category')->orderBy('order')->paginate(20);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        $categories = PortfolioCategory::active()->orderBy('order')->get();
        return view('admin.portfolios.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolios',
            'category_id' => 'nullable|exists:portfolio_categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'completed_date' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->fileUploadService->upload($request->file('featured_image'), 'portfolios');
        }

        if ($request->hasFile('gallery')) {
            $data['gallery'] = $this->fileUploadService->uploadMultiple($request->file('gallery'), 'portfolios/gallery');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Portfolio::max('order') + 1;

        $portfolio = Portfolio::create($data);

        return redirect()->route('admin.portfolios.edit', $portfolio)
            ->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $categories = PortfolioCategory::active()->orderBy('order')->get();
        return view('admin.portfolios.form', compact('portfolio', 'categories'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolios,slug,' . $portfolio->id,
            'category_id' => 'nullable|exists:portfolio_categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'completed_date' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('featured_image')) {
            $this->fileUploadService->delete($portfolio->featured_image);
            $data['featured_image'] = $this->fileUploadService->upload($request->file('featured_image'), 'portfolios');
        }

        if ($request->hasFile('gallery')) {
            $this->fileUploadService->deleteMultiple($portfolio->gallery ?? []);
            $data['gallery'] = $this->fileUploadService->uploadMultiple($request->file('gallery'), 'portfolios/gallery');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $portfolio->update($data);

        return back()->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->fileUploadService->delete($portfolio->featured_image);
        $this->fileUploadService->deleteMultiple($portfolio->gallery ?? []);
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio deleted successfully.');
    }

    // Category Management
    public function categories()
    {
        $categories = PortfolioCategory::orderBy('order')->get();
        return view('admin.portfolios.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolio_categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = PortfolioCategory::max('order') + 1;

        PortfolioCategory::create($data);

        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, PortfolioCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolio_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(PortfolioCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
