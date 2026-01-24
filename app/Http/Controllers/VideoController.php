<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::where('is_active', true)->orderBy('order');
        
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        $videos = $query->get();
        $categories = Video::where('is_active', true)->whereNotNull('category')->distinct()->pluck('category');
        
        return view('frontend.videos.index', compact('videos', 'categories'));
    }
}
