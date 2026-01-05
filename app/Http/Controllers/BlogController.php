<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index(?string $slug = null)
    {
        $categories = BlogCategory::active()
            ->withCount('publishedBlogs')
            ->orderBy('order')
            ->get();

        $blogsQuery = Blog::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc');

        $activeCategory = null;
        if ($slug) {
            $category = BlogCategory::where('slug', $slug)->firstOrFail();
            $blogsQuery->where('category_id', $category->id);
            $activeCategory = $slug;
        }

        $blogs = $blogsQuery->paginate(9);

        $featuredPost = Blog::published()
            ->featured()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->first();

        return view('frontend.blog.index', compact('blogs', 'categories', 'activeCategory', 'featuredPost'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->published()
            ->with(['category', 'author'])
            ->firstOrFail();

        $blog->incrementViews();

        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category_id, function ($query) use ($blog) {
                $query->where('category_id', $blog->category_id);
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        $categories = BlogCategory::active()
            ->withCount('publishedBlogs')
            ->orderBy('order')
            ->get();

        return view('frontend.blog.show', compact('blog', 'relatedBlogs', 'categories'));
    }
}
