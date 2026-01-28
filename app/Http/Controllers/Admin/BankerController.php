<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banker;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class BankerController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index(Request $request)
    {
        $query = Banker::orderBy('order');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $bankers = $query->paginate(20)->withQueryString();
        return view('admin.bankers.index', compact('bankers'));
    }

    public function create()
    {
        return view('admin.bankers.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|max:20480',
            'description' => 'nullable|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->fileUploadService->upload($request->file('logo'), 'bankers');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!isset($data['order'])) {
            $data['order'] = Banker::max('order') + 1;
        }

        Banker::create($data);

        return redirect()->route('admin.bankers.index')
            ->with('success', 'Banker created successfully.');
    }

    public function edit(Banker $banker)
    {
        return view('admin.bankers.form', compact('banker'));
    }

    public function update(Request $request, Banker $banker)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:20480',
            'description' => 'nullable|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($banker->logo) {
                $this->fileUploadService->delete($banker->logo);
            }
            $data['logo'] = $this->fileUploadService->upload($request->file('logo'), 'bankers');
        }

        $data['is_active'] = $request->boolean('is_active');
        
        $banker->update($data);

        return redirect()->route('admin.bankers.index')
            ->with('success', 'Banker updated successfully.');
    }

    public function destroy(Banker $banker)
    {
        if ($banker->logo) {
            $this->fileUploadService->delete($banker->logo);
        }
        $banker->delete();

        return redirect()->route('admin.bankers.index')
            ->with('success', 'Banker deleted successfully.');
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
                Banker::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Selected items activated.';
                break;
            case 'deactivate':
                Banker::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Selected items deactivated.';
                break;
            case 'delete':
                $bankers = Banker::whereIn('id', $ids)->get();
                foreach ($bankers as $banker) {
                    if ($banker->logo) {
                        $this->fileUploadService->delete($banker->logo);
                    }
                    $banker->delete();
                }
                $message = 'Selected items deleted.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }
}
