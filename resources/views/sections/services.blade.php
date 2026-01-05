@php
    $title = $data['title'] ?? 'Our Services';
    $subtitle = $data['subtitle'] ?? 'What We Offer';
    $services = $data['services'] ?? collect();
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

        <!-- Services Grid -->
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

        <!-- View All Button -->
        <div class="text-center mt-12" data-animate="animate-fade-in-up">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gradient-primary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                View All Services
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
