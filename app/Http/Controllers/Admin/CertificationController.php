<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index(Request $request)
    {
        $query = Certification::orderBy('order');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('issuing_authority', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%");
            });
        }

        $certifications = $query->paginate(20)->withQueryString();
        return view('admin.certifications.index', compact('certifications'));
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (empty($ids)) {
            return back()->with('error', 'No items selected.');
        }

        switch ($action) {
            case 'activate':
                Certification::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Selected items activated.';
                break;
            case 'deactivate':
                Certification::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Selected items deactivated.';
                break;
            case 'delete':
                $certs = Certification::whereIn('id', $ids)->get();
                foreach ($certs as $cert) {
                    if ($cert->image) {
                        $this->fileUploadService->delete($cert->image);
                    }
                    $cert->delete();
                }
                $message = 'Selected items deleted.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }

    public function create()
    {
        return view('admin.certifications.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'image' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'certificate_number' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'certifications');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!isset($data['order'])) {
            $data['order'] = Certification::max('order') + 1;
        }

        Certification::create($data);

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification created successfully.');
    }

    public function edit(Certification $certification)
    {
        return view('admin.certifications.form', compact('certification'));
    }

    public function update(Request $request, Certification $certification)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'image' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'certificate_number' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->fileUploadService->delete($certification->image);
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'certifications');
        }

        $data['is_active'] = $request->boolean('is_active');
        
        $certification->update($data);

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification updated successfully.');
    }

    public function destroy(Certification $certification)
    {
        if ($certification->image) {
            $this->fileUploadService->delete($certification->image);
        }
        $certification->delete();

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification deleted successfully.');
    }
}
