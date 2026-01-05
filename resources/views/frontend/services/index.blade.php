@extends('layouts.frontend')

@section('title', 'Our Services - ' . ($siteSettings['site_title'] ?? 'IT Business'))

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/></svg>'); background-size: 50px 50px;"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4" data-animate="animate-fade-in-up">Our Services</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto" data-animate="animate-fade-in-up" data-delay="200">
                    Comprehensive IT solutions tailored to your business needs
                </p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $index => $service)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover-lift overflow-hidden group" data-animate="animate-fade-in-up" data-delay="{{ $index * 100 }}">
                        @if($service->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @endif

                        <div class="p-6">
                            @if($service->icon)
                                <div class="w-14 h-14 gradient-primary rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="{{ $service->icon }} text-2xl text-white"></i>
                                </div>
                            @endif

                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">{{ $service->name }}</h3>

                            @if($service->short_description)
                                <p class="text-gray-600 mb-4">{{ $service->short_description }}</p>
                            @endif

                            <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center text-primary font-semibold hover:gap-2 transition-all">
                                Learn More
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('sections.cta')
@endsection
