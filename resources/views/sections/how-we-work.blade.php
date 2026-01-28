@php
    $title = $data['title'] ?? 'How We Work';
    $subtitle = $data['subtitle'] ?? 'Our Process';
    $steps = $data['steps'] ?? collect();
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="animate-fade-in-up">
            @if($subtitle)
                <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
        </div>

        <!-- Steps -->
        <div class="relative">
            <!-- Connecting Line -->
            <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-0.5 bg-gray-200 transform -translate-y-1/2"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($steps as $index => $step)
                    <div class="relative text-center" data-animate="animate-fade-in-up" data-delay="{{ $index * 150 }}">
                        <!-- Step Number -->
                        <div class="relative z-10 w-20 h-20 gradient-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            @if($step->icon)
                                <i class="{{ $step->icon }} text-2xl text-white"></i>
                            @else
                                <span class="text-2xl font-bold text-white">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step->title }}</h3>
                        @if($step->description)
                            <p class="text-gray-600">{{ $step->description }}</p>
                        @endif

                        <!-- Arrow (except last) -->
                        @if(!$loop->last)
                            <div class="hidden lg:block absolute top-10 right-0 transform translate-x-1/2 text-primary">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
