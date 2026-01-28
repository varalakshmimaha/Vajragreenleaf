<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    public function index(Request $request)
    {
        $query = Video::orderBy('order');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $videos = $query->paginate(20)->withQueryString();
        return view('admin.videos.index', compact('videos'));
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
                Video::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Selected items activated.';
                break;
            case 'deactivate':
                Video::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Selected items deactivated.';
                break;
            case 'delete':
                $videos = Video::whereIn('id', $ids)->get();
                foreach ($videos as $vid) {
                    if ($vid->type === 'upload' && $vid->url) {
                        $this->fileUploadService->delete($vid->url);
                    }
                    if ($vid->thumbnail) {
                        $this->fileUploadService->delete($vid->thumbnail);
                    }
                    $vid->delete();
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
        return view('admin.videos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:youtube,vimeo,upload',
            'url' => 'required_if:type,youtube,vimeo|nullable|string|max:255',
            'video_file' => 'required_if:type,upload|nullable|mimes:mp4,webm,ogg|max:20480',
            'thumbnail' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('video_file')) {
            $data['url'] = $this->fileUploadService->upload($request->file('video_file'), 'videos/files');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->fileUploadService->upload($request->file('thumbnail'), 'videos/thumbnails');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!isset($data['order'])) {
            $data['order'] = Video::max('order') + 1;
        }

        Video::create($data);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video created successfully.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.form', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:youtube,vimeo,upload',
            'url' => 'required_if:type,youtube,vimeo|nullable|string|max:255',
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:20480',
            'thumbnail' => 'nullable|image|max:20480',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('video_file')) {
            if ($video->type === 'upload' && $video->url) {
                $this->fileUploadService->delete($video->url);
            }
            $data['url'] = $this->fileUploadService->upload($request->file('video_file'), 'videos/files');
        }

        if ($request->hasFile('thumbnail')) {
            $this->fileUploadService->delete($video->thumbnail);
            $data['thumbnail'] = $this->fileUploadService->upload($request->file('thumbnail'), 'videos/thumbnails');
        }

        $data['is_active'] = $request->boolean('is_active');
        
        $video->update($data);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        if ($video->type === 'upload' && $video->url) {
            $this->fileUploadService->delete($video->url);
        }
        if ($video->thumbnail) {
            $this->fileUploadService->delete($video->thumbnail);
        }
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully.');
    }
}
