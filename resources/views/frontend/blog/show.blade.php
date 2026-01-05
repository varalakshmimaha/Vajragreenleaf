@extends('layouts.frontend')

@section('title', $blog->meta_title ?? $blog->title)
@section('meta_description', $blog->meta_description ?? $blog->excerpt)

@section('content')
    <!-- Article Header -->
    <article>
        <header class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center" data-animate="animate-fade-in">
                    @if($blog->category)
                        <a href="{{ route('blog.category', $blog->category->slug) }}"
                           class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-medium rounded-full mb-4 hover:bg-primary/20 transition-colors">
                            {{ $blog->category->name }}
                        </a>
                    @endif

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-heading">{{ $blog->title }}</h1>

                    <div class="flex items-center justify-center text-gray-500 space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 gradient-primary rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $blog->author_name }}</span>
                        </div>
                        <span>•</span>
                        <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $blog->read_time }} min read</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($blog->featured_image)
            <div class="container mx-auto px-4 -mt-8">
                <div class="max-w-4xl mx-auto">
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                        class="w-full rounded-xl shadow-lg animate-fade-in-up">
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto">
                    <div class="prose prose-lg max-w-none animate-fade-in">
                        {!! $blog->content !!}
                    </div>

                    <!-- Share -->
                    <div class="border-t border-b border-gray-200 py-6 my-12">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900">Share this article</span>
                            <div class="flex space-x-4">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                                   target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blog->title) }}"
                                   target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-700 hover:text-white transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                   target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Author Box -->
                    <div class="bg-gray-50 rounded-xl p-6 flex items-start space-x-4">
                        <div class="w-16 h-16 gradient-primary rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                            {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $blog->author_name }}</h4>
                            <p class="text-gray-600 mt-1">Author at {{ $siteSettings['site_title'] ?? 'Our Company' }}. Passionate about technology and innovation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    @if($relatedBlogs->count() > 0)
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 font-heading" data-animate="animate-fade-in">Related Articles</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    @foreach($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}"
                           class="group card-modern overflow-hidden" data-animate="animate-fade-in-up">
                            <div class="h-40 overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full gradient-primary"></div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 font-heading">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-500 mt-2">{{ $related->published_at->format('M d, Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
