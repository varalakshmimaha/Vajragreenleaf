<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServicePlan;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $services = Service::with('plans')->orderBy('order')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services',
            'icon' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->fileUploadService->upload($request->file('banner_image'), 'services');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'services');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Service::max('order') + 1;

        $service = Service::create($data);

        return redirect()->route('admin.services.edit', $service)
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $service->load('plans');
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'icon' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('banner_image')) {
            $this->fileUploadService->delete($service->banner_image);
            $data['banner_image'] = $this->fileUploadService->upload($request->file('banner_image'), 'services');
        }

        if ($request->hasFile('image')) {
            $this->fileUploadService->delete($service->image);
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'services');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $service->update($data);

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->fileUploadService->delete($service->banner_image);
        $this->fileUploadService->delete($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function storePlan(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'price_label' => 'nullable|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $service->plans()->max('order') + 1;

        $service->plans()->create($data);

        return back()->with('success', 'Plan added successfully.');
    }

    public function updatePlan(Request $request, ServicePlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'price_label' => 'nullable|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');

        $plan->update($data);

        return back()->with('success', 'Plan updated successfully.');
    }

    public function destroyPlan(ServicePlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted successfully.');
    }
}
