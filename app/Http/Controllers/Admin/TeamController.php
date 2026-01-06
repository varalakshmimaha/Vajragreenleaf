<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $team = Team::orderBy('order')->paginate(20);
        return view('admin.team.index', compact('team'));
    }

    public function create()
    {
        return view('admin.team.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:teams',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'social_links' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->fileUploadService->upload($request->file('photo'), 'team');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Team::max('order') + 1;

        Team::create($data);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member added successfully.');
    }

    public function edit(Team $team)
    {
        $member = $team;
        return view('admin.team.form', compact('member'));
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:teams,slug,' . $team->id,
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'social_links' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $this->fileUploadService->delete($team->photo);
            $data['photo'] = $this->fileUploadService->upload($request->file('photo'), 'team');
        }

        $data['is_active'] = $request->boolean('is_active');
        $team->update($data);

        return back()->with('success', 'Team member updated successfully.');
    }

    public function destroy(Team $team)
    {
        $this->fileUploadService->delete($team->photo);
        $team->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:teams,id',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            Team::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
