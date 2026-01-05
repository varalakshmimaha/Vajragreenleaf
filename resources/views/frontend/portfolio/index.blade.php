@extends('layouts.frontend')

@section('title', 'Our Portfolio')

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white" data-animate="animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Our Portfolio</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">Explore our successful projects and see how we've helped businesses transform through technology.</p>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    @if($categories->count() > 0)
        <section class="py-8 bg-white border-b sticky top-16 z-40">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('portfolio.index') }}"
                       class="px-4 py-2 rounded-full transition-colors {{ !$activeCategory ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('portfolio.category', $category->slug) }}"
                           class="px-4 py-2 rounded-full transition-colors {{ $activeCategory == $category->slug ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Portfolio Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($portfolios as $portfolio)
                    <a href="{{ route('portfolio.show', $portfolio->slug) }}"
                       class="group card-modern overflow-hidden" data-animate="animate-fade-in-up">
                        <div class="relative h-64 overflow-hidden">
                            @if($portfolio->featured_image)
                                <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full gradient-primary flex items-center justify-center">
                                    <i class="fas fa-image text-6xl text-white/50"></i>
                                </div>
                            @endif

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <div class="p-6 text-white">
                                    <span class="text-sm font-medium">View Project</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($portfolio->category)
                                <span class="text-sm text-primary font-medium">{{ $portfolio->category->name }}</span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 mt-1 mb-2 group-hover:text-primary transition-colors font-heading">{{ $portfolio->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($portfolio->short_description, 100) }}</p>

                            @if($portfolio->technologies && count($portfolio->technologies) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($portfolio->technologies, 0, 3) as $tech)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">{{ $tech }}</span>
                                    @endforeach
                                    @if(count($portfolio->technologies) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">+{{ count($portfolio->technologies) - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <i class="fas fa-folder-open text-6xl mb-4"></i>
                        <p class="text-xl">No projects found.</p>
                    </div>
                @endforelse
            </div>

            @if($portfolios->hasPages())
                <div class="mt-12">
                    {{ $portfolios->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-heading" data-animate="animate-fade-in">Ready to Start Your Project?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-animate="animate-fade-in">Let's discuss how we can bring your vision to life with our expertise.</p>
            <a href="{{ route('contact') }}" class="inline-block btn-primary text-lg" data-animate="animate-fade-in-up">
                Start a Project
            </a>
        </div>
    </section>
@endsection
