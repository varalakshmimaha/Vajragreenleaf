@extends('layouts.frontend')

@section('title', $portfolio->meta_title ?? $portfolio->title)
@section('meta_description', $portfolio->meta_description ?? $portfolio->short_description)

@section('content')
    <!-- Hero -->
    <section class="relative">
        @if($portfolio->featured_image)
            <div class="h-[50vh] relative">
                <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            </div>
        @else
            <div class="h-[40vh] gradient-primary"></div>
        @endif

        <div class="absolute bottom-0 left-0 right-0 pb-12">
            <div class="container mx-auto px-4">
                <div class="text-white" data-animate="animate-fade-in">
                    @if($portfolio->category)
                        <span class="inline-block px-3 py-1 bg-primary text-sm rounded-full mb-4">{{ $portfolio->category->name }}</span>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">{{ $portfolio->title }}</h1>
                    <p class="text-xl text-gray-200 max-w-2xl">{{ $portfolio->short_description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Details -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="prose prose-lg max-w-none animate-fade-in">
                        {!! $portfolio->description !!}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6 animate-fade-in-up">
                    <!-- Project Info -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Project Details</h3>

                        @if($portfolio->client_name)
                            <div class="mb-4">
                                <span class="block text-sm text-gray-500">Client</span>
                                <span class="text-gray-900 font-medium">{{ $portfolio->client_name }}</span>
                            </div>
                        @endif

                        @if($portfolio->completed_date)
                            <div class="mb-4">
                                <span class="block text-sm text-gray-500">Completed</span>
                                <span class="text-gray-900 font-medium">{{ $portfolio->completed_date->format('F Y') }}</span>
                            </div>
                        @endif

                        @if($portfolio->project_url)
                            <div class="mb-4">
                                <span class="block text-sm text-gray-500">Website</span>
                                <a href="{{ $portfolio->project_url }}" target="_blank" class="text-primary hover:underline font-medium">
                                    Visit Site <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Technologies -->
                    @if($portfolio->technologies && count($portfolio->technologies) > 0)
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 font-heading">Technologies Used</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($portfolio->technologies as $tech)
                                    <span class="px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- CTA -->
                    <div class="gradient-primary rounded-xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-2">Have a Similar Project?</h3>
                        <p class="text-white/80 mb-4">Let's discuss how we can help bring your vision to life.</p>
                        <a href="{{ route('contact') }}" class="block text-center bg-white text-primary px-4 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Get in Touch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Projects -->
    @if($relatedPortfolios->count() > 0)
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 font-heading" data-animate="animate-fade-in">Related Projects</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPortfolios as $related)
                        <a href="{{ route('portfolio.show', $related->slug) }}"
                           class="group card-modern overflow-hidden" data-animate="animate-fade-in-up">
                            <div class="h-48 overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full gradient-primary"></div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-gray-900 group-hover:text-primary transition-colors font-heading">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($related->short_description, 60) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
