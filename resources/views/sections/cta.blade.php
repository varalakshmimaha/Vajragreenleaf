@php
    $title = $data['title'] ?? 'Ready to Start Your Project?';
    $subtitle = $data['content']['subtitle'] ?? '';
    $description = $data['description'] ?? 'Let\'s work together to bring your ideas to life.';
    $cta_text = $data['cta_text'] ?? 'Get In Touch';
    $cta_url = $data['cta_url'] ?? route('contact');
    $background = $data['background'] ?? 'gradient';
@endphp

<section class="py-20 {{ $background === 'gradient' ? 'gradient-primary' : 'bg-gray-900' }} text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center" data-animate="animate-fade-in-up">
            <!-- @if($subtitle)
                <p class="text-white/80 font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif -->

            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">{{ $title }}</h2>

            @if($subtitle)
                <p class="text-xl text-white/80 mb-8">{{ $subtitle }}</p>
            @endif

            <a href="{{ $cta_url }}" class="inline-flex items-center bg-white text-primary px-8 py-4 rounded-full font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300">
                {{ $cta_text }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
