<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\JobApplication;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::withCount('applications')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.careers.index', compact('careers'));
    }

    public function create()
    {
        return view('admin.careers.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:careers',
            'department' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote,internship',
            'experience_level' => 'required|in:entry,mid,senior,lead,executive',
            'salary_range' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'positions' => 'nullable|integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['positions'] = $data['positions'] ?? 1;

        Career::create($data);

        return redirect()->route('admin.careers.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function edit(Career $career)
    {
        return view('admin.careers.form', compact('career'));
    }

    public function update(Request $request, Career $career)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:careers,slug,' . $career->id,
            'department' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote,internship',
            'experience_level' => 'required|in:entry,mid,senior,lead,executive',
            'salary_range' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'positions' => 'nullable|integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $career->update($data);

        return back()->with('success', 'Job posting updated successfully.');
    }

    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()->route('admin.careers.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    public function applications(Career $career = null)
    {
        $query = JobApplication::with('career')
            ->orderBy('created_at', 'desc');

        if ($career) {
            $query->where('career_id', $career->id);
        }

        $applications = $query->paginate(20);
        $careers = Career::orderBy('title')->get();

        return view('admin.careers.applications', compact('applications', 'careers', 'career'));
    }

    public function showApplication(JobApplication $application)
    {
        return response()->json($application->load('career'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,interviewed,offered,hired,rejected',
            'notes' => 'nullable|string|max:2000',
        ]);

        $application->update($data);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function destroyApplication(JobApplication $application)
    {
        if ($application->resume) {
            app(FileUploadService::class)->delete($application->resume);
        }
        if ($application->cover_letter_file) {
            app(FileUploadService::class)->delete($application->cover_letter_file);
        }

        $application->delete();

        return back()->with('success', 'Application deleted successfully.');
    }
}
