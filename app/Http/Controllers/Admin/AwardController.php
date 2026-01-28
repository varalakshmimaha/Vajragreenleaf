<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index(Request $request)
    {
        $query = Award::orderBy('order');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('awarding_organization', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $awards = $query->paginate(20)->withQueryString();
        return view('admin.awards.index', compact('awards'));
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
                Award::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Selected items activated.';
                break;
            case 'deactivate':
                Award::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Selected items deactivated.';
                break;
            case 'delete':
                $awards = Award::whereIn('id', $ids)->get();
                foreach ($awards as $award) {
                    if ($award->image) {
                        $this->fileUploadService->delete($award->image);
                    }
                    $award->delete();
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
        return view('admin.awards.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'awarding_organization' => 'required|string|max:255',
            'year' => 'nullable|integer|digits:4',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'awards');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!isset($data['order'])) {
            $data['order'] = Award::max('order') + 1;
        }

        Award::create($data);

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award created successfully.');
    }

    public function edit(Award $award)
    {
        return view('admin.awards.form', compact('award'));
    }

    public function update(Request $request, Award $award)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'awarding_organization' => 'required|string|max:255',
            'year' => 'nullable|integer|digits:4',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->fileUploadService->delete($award->image);
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'awards');
        }

        $data['is_active'] = $request->boolean('is_active');
        
        $award->update($data);

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award updated successfully.');
    }

    public function destroy(Award $award)
    {
        if ($award->image) {
            $this->fileUploadService->delete($award->image);
        }
        $award->delete();

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award deleted successfully.');
    }
}
