<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\JobApplication;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $careers = Career::active()
            ->open()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $departments = Career::active()
            ->open()
            ->distinct()
            ->pluck('department')
            ->filter()
            ->values();

        $jobTypes = Career::active()
            ->open()
            ->distinct()
            ->pluck('job_type')
            ->values();

        return view('frontend.careers.index', compact('careers', 'departments', 'jobTypes'));
    }

    public function show(string $slug)
    {
        $career = Career::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $relatedJobs = Career::active()
            ->open()
            ->where('id', '!=', $career->id)
            ->where(function ($query) use ($career) {
                $query->where('department', $career->department)
                    ->orWhere('job_type', $career->job_type);
            })
            ->limit(3)
            ->get();

        return view('frontend.careers.show', compact('career', 'relatedJobs'));
    }

    public function apply(Request $request, string $slug)
    {
        $career = Career::where('slug', $slug)
            ->active()
            ->open()
            ->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string|max:5000',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|string|max:100',
            'available_from' => 'nullable|date',
        ]);

        $data['career_id'] = $career->id;

        if ($request->hasFile('resume')) {
            $data['resume'] = $this->fileUploadService->upload($request->file('resume'), 'applications/resumes');
        }

        if ($request->hasFile('cover_letter_file')) {
            $data['cover_letter_file'] = $this->fileUploadService->upload($request->file('cover_letter_file'), 'applications/cover-letters');
        }

        JobApplication::create($data);

        return back()->with('success', 'Your application has been submitted successfully! We will contact you soon.');
    }
}
