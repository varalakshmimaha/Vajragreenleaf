<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCategory;

class GalleryController extends Controller
{
    public function index(?string $slug = null)
    {
        $categories = GalleryCategory::active()
            ->withCount('activeGalleries')
            ->ordered()
            ->get();

        $galleriesQuery = Gallery::active()
            ->with('category')
            ->ordered();

        $activeCategory = null;
        if ($slug) {
            $category = GalleryCategory::where('slug', $slug)->firstOrFail();
            $galleriesQuery->where('category_id', $category->id);
            $activeCategory = $slug;
        }

        $galleries = $galleriesQuery->paginate(24);

        $featuredImages = Gallery::active()
            ->featured()
            ->ordered()
            ->limit(5)
            ->get();

        return view('frontend.gallery.index', compact('galleries', 'categories', 'activeCategory', 'featuredImages'));
    }
}
