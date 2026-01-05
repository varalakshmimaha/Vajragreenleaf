@php
    $title = $data['title'] ?? 'Latest Blog Posts';
    $subtitle = $data['subtitle'] ?? 'Our Blog';
    $blogs = $data['blogs'] ?? collect();
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="animate-fade-in-up">
            @if($subtitle)
                <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <div class="w-20 h-1 gradient-primary mx-auto rounded-full"></div>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogs as $index => $blog)
                <article class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover-lift overflow-hidden group" data-animate="animate-fade-in-up" data-delay="{{ $index * 100 }}">
                    <!-- Image -->
                    @if($blog->featured_image)
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                    @endif

                    <div class="p-6">
                        <!-- Meta -->
                        <div class="flex items-center text-sm text-gray-500 mb-3 space-x-4">
                            @if($blog->category)
                                <span class="text-primary font-medium">{{ $blog->category->name }}</span>
                            @endif
                            <span>{{ $blog->published_at?->format('M d, Y') }}</span>
                            <span>{{ $blog->read_time }} min read</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors line-clamp-2">
                            <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                        </h3>

                        <!-- Excerpt -->
                        @if($blog->excerpt)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $blog->excerpt }}</p>
                        @endif

                        <!-- Author & Link -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            @if($blog->author)
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($blog->author->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $blog->author->name }}</span>
                                </div>
                            @endif

                            <a href="{{ route('blog.show', $blog->slug) }}" class="text-primary font-semibold hover:gap-2 transition-all inline-flex items-center">
                                Read More
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12" data-animate="animate-fade-in-up">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gradient-primary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                View All Posts
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
