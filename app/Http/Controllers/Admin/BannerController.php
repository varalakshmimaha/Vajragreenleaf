<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
            'video' => 'nullable|mimes:mp4,webm|max:20480',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
            'cta_text_2' => 'nullable|string|max:50',
            'cta_url_2' => 'nullable|string|max:255',
            'type' => 'in:image,video',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'banners');
        }

        if ($request->hasFile('video')) {
            $data['video'] = $this->fileUploadService->upload($request->file('video'), 'banners/videos');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = Banner::max('order') + 1;

        Banner::create($data);

        return back()->with('success', 'Banner added successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
            'video' => 'nullable|mimes:mp4,webm|max:20480',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
            'cta_text_2' => 'nullable|string|max:50',
            'cta_url_2' => 'nullable|string|max:255',
            'type' => 'in:image,video',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->fileUploadService->delete($banner->image);
            $data['image'] = $this->fileUploadService->upload($request->file('image'), 'banners');
        }

        if ($request->hasFile('video')) {
            $this->fileUploadService->delete($banner->video);
            $data['video'] = $this->fileUploadService->upload($request->file('video'), 'banners/videos');
        }

        $data['is_active'] = $request->boolean('is_active');
        $banner->update($data);

        return back()->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $this->fileUploadService->delete($banner->image);
        $this->fileUploadService->delete($banner->video);
        $banner->delete();

        return back()->with('success', 'Banner deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:banners,id',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            Banner::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
