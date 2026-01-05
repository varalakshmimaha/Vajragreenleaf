@php
    $title = $data['title'] ?? 'Our Portfolio';
    $subtitle = $data['subtitle'] ?? 'Recent Projects';
    $portfolios = $data['portfolios'] ?? collect();
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="animate-fade-in-up">
            @if($subtitle)
                <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <div class="w-20 h-1 gradient-primary mx-auto rounded-full"></div>
        </div>

        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portfolios as $index => $portfolio)
                <div class="group relative overflow-hidden rounded-2xl shadow-lg hover-lift" data-animate="animate-fade-in-up" data-delay="{{ $index * 100 }}">
                    <!-- Image -->
                    <div class="aspect-[4/3] overflow-hidden">
                        @if($portfolio->featured_image)
                            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full gradient-primary"></div>
                        @endif
                    </div>

                    <!-- Overlay -->
                    <div class="absolute inset-0 gradient-primary opacity-0 group-hover:opacity-90 transition-opacity duration-300"></div>

                    <!-- Content -->
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        @if($portfolio->category)
                            <span class="text-sm font-medium mb-2">{{ $portfolio->category->name }}</span>
                        @endif
                        <h3 class="text-xl font-bold mb-2">{{ $portfolio->title }}</h3>
                        @if($portfolio->short_description)
                            <p class="text-sm opacity-90 mb-4 line-clamp-2">{{ $portfolio->short_description }}</p>
                        @endif
                        <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="inline-flex items-center text-white font-semibold hover:gap-2 transition-all">
                            View Project
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Static Title (visible by default) -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent group-hover:opacity-0 transition-opacity duration-300">
                        <h3 class="text-white font-bold">{{ $portfolio->title }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12" data-animate="animate-fade-in-up">
            <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gradient-primary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                View All Projects
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
