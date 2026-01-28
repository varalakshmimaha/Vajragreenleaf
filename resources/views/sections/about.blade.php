@php
    $title = $data['title'] ?? 'About Our Company';
    $subtitle = $data['subtitle'] ?? '';
    $content = $data['content'] ?? '';
    $description = $data['description'] ?? '';
    $image = $data['image'] ?? null;
    $features = $data['features'] ?? [];
    // Handle features as string (comma-separated) or array
    if (is_string($features) && !empty($features)) {
        $features = array_map('trim', explode(',', $features));
    }
    if (!is_array($features)) {
        $features = [];
    }
    $showButton = $data['show_button'] ?? true;
    $buttonText = $data['button_text'] ?? 'Learn More About Us';
    $buttonUrl = $data['button_url'] ?? route('page.show', 'about-us');
@endphp

<section class="py-24 bg-gradient-to-br from-gray-50 via-white to-gray-50 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 {{ $image ? 'lg:grid-cols-2' : '' }} gap-16 items-center">

            <!-- Content - Left Side -->
            <div data-animate="animate-fade-in-left" class="{{ !$image ? 'max-w-4xl mx-auto text-center' : '' }}">
                @if($subtitle)
                    <div class="inline-flex items-center mb-4">
                        <span class="w-12 h-[2px] bg-gradient-to-r from-green-500 to-emerald-600 mr-3"></span>
                        <p class="text-green-600 font-semibold uppercase tracking-wider text-sm">{{ $subtitle }}</p>
                    </div>
                @endif

                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">{{ $title }}</h2>

                @if($content)
                    <div class="prose prose-lg text-gray-600 mb-6 leading-relaxed">
                        {!! $content !!}
                    </div>
                @endif

                @if($description)
                    <p class="text-gray-500 mb-8 text-lg leading-relaxed">{{ $description }}</p>
                @endif

                @if(count($features) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10 {{ !$image ? 'max-w-2xl mx-auto' : '' }}">
                        @foreach($features as $feature)
                            <div class="flex items-center space-x-3 group {{ !$image ? 'justify-center' : '' }}">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($showButton)
                <div class="flex items-center gap-4 {{ !$image ? 'justify-center' : '' }}">
                    <a href="{{ $buttonUrl }}" class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-full font-semibold shadow-lg hover:shadow-xl hover:shadow-green-500/30 hover:-translate-y-1 transition-all duration-300">
                        {{ $buttonText }}
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            <!-- Image - Right Side -->
            @if($image)
                <div class="relative lg:pl-8" data-animate="animate-fade-in-right">
                    <!-- Decorative Elements -->
                    <div class="absolute -top-8 -right-8 w-72 h-72 bg-gradient-to-br from-green-200 to-emerald-200 rounded-full opacity-40 blur-3xl"></div>
                    <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-gradient-to-br from-green-300 to-emerald-300 rounded-full opacity-30 blur-2xl"></div>

                    <!-- Main Image Container -->
                    <div class="relative z-10">
                        <!-- Image Frame -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" class="w-full h-auto object-cover">
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        </div>

                        <!-- Decorative Border -->
                        <div class="absolute -bottom-4 -right-4 w-full h-full border-4 border-green-500/20 rounded-3xl -z-10"></div>

                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-4 z-20">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">10+</p>
                                    <p class="text-sm text-gray-500">Years Experience</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Stats Badge (Top Right) -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 z-20">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">500+</p>
                                    <p class="text-sm text-gray-500">Happy Clients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
