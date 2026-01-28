@php
    $title = $data['title'] ?? 'About Our Company';
    $subtitle = $data['subtitle'] ?? '';
    $content = $data['content'] ?? '';
    $description = $data['description'] ?? '';
    $image = $data['image'] ?? null;
    $features = $data['features'] ?? [];
    $showButton = $data['show_button'] ?? true;
    $buttonText = $data['button_text'] ?? 'Learn More About Us';
    $buttonUrl = $data['button_url'] ?? route('page.show', 'about-us');
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Image -->
            @if($image)
                <div class="relative" data-animate="animate-fade-in-left">
                    <div class="relative z-10">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" class="rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-full h-full gradient-primary rounded-2xl opacity-20"></div>
                </div>
            @endif

            <!-- Content -->
            <div data-animate="animate-fade-in-right">
                @if($subtitle)
                    <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
                @endif

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $title }}</h2>

                @if($content)
                    <div class="prose prose-lg text-gray-600 mb-6">
                        {!! $content !!}
                    </div>
                @endif

                @if($description)
                    <p class="text-gray-500 mb-8">{{ $description }}</p>
                @endif

                @if(count($features) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        @foreach($features as $feature)
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 rounded-full gradient-primary flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

@if($showButton)
                <a href="{{ $buttonUrl }}" class="inline-flex items-center gradient-primary text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                    {{ $buttonText }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
