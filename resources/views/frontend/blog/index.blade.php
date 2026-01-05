@extends('layouts.frontend')

@section('title', 'Blog')

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white" data-animate="animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Our Blog</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">Insights, tutorials, and updates from our team of technology experts.</p>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    @if($categories->count() > 0)
        <section class="py-8 bg-white border-b">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('blog.index') }}"
                       class="px-4 py-2 rounded-full transition-colors {{ !$activeCategory ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}"
                           class="px-4 py-2 rounded-full transition-colors {{ $activeCategory == $category->slug ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Post -->
    @if($featuredPost)
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <a href="{{ route('blog.show', $featuredPost->slug) }}" class="group block card-modern overflow-hidden" data-animate="animate-fade-in">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="h-64 lg:h-auto overflow-hidden">
                            @if($featuredPost->featured_image)
                                <img src="{{ asset('storage/' . $featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full gradient-primary flex items-center justify-center">
                                    <i class="fas fa-newspaper text-6xl text-white/50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full mb-4 w-fit">Featured</span>
                            @if($featuredPost->category)
                                <span class="text-primary font-medium mb-2">{{ $featuredPost->category->name }}</span>
                            @endif
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4 group-hover:text-primary transition-colors font-heading">{{ $featuredPost->title }}</h2>
                            <p class="text-gray-600 mb-6">{{ $featuredPost->excerpt ?? Str::limit(strip_tags($featuredPost->content), 200) }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <span>{{ $featuredPost->author_name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredPost->published_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredPost->read_time }} min read</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    @endif

    <!-- Blog Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($blogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}"
                       class="group card-modern overflow-hidden" data-animate="animate-fade-in-up">
                        <div class="h-48 overflow-hidden">
                            @if($blog->featured_image)
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full gradient-primary flex items-center justify-center">
                                    <i class="fas fa-newspaper text-4xl text-white/50"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            @if($blog->category)
                                <span class="text-sm text-primary font-medium">{{ $blog->category->name }}</span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 mt-1 mb-3 group-hover:text-primary transition-colors line-clamp-2 font-heading">{{ $blog->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 100) }}</p>

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $blog->published_at->format('M d, Y') }}</span>
                                <span>{{ $blog->read_time }} min read</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <i class="fas fa-newspaper text-6xl mb-4"></i>
                        <p class="text-xl">No blog posts found.</p>
                    </div>
                @endforelse
            </div>

            @if($blogs->hasPages())
                <div class="mt-12">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
