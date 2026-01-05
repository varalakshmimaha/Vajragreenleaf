<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $testimonials = Testimonial::orderBy('order')->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.form');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->fileUploadService->upload($request->file('photo'), 'testimonials');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Testimonial::max('order') + 1;

        Testimonial::create($data);

        return back()->with('success', 'Testimonial added successfully.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $this->fileUploadService->delete($testimonial->photo);
            $data['photo'] = $this->fileUploadService->upload($request->file('photo'), 'testimonials');
        }

        $data['is_active'] = $request->boolean('is_active');
        $testimonial->update($data);

        return back()->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->fileUploadService->delete($testimonial->photo);
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted successfully.');
    }
}
