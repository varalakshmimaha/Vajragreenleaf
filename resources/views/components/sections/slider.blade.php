{{-- Image Slider Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $gallery = $section->gallery ?? [];
    $sliderId = 'slider-' . ($section->id ?? uniqid());
@endphp

<div>
    {{-- Header --}}
    @if($section->title || $section->subtitle)
        <div class="mb-12 {{ $contentClasses }}">
            @if($section->subtitle)
                <p class="text-primary font-semibold mb-2 uppercase tracking-wider">
                    {{ $section->subtitle }}
                </p>
            @endif

            @if($section->title)
                <h2 class="{{ $titleClasses }} mb-4">
                    {{ $section->title }}
                </h2>
            @endif
        </div>
    @endif

    {{-- Slider --}}
    @if(count($gallery) > 0)
        <div class="relative" x-data="{ currentSlide: 0, totalSlides: {{ count($gallery) }} }">
            {{-- Slides --}}
            <div class="overflow-hidden rounded-xl">
                <div class="flex transition-transform duration-500"
                     :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                    @foreach($gallery as $index => $image)
                        <div class="w-full flex-shrink-0">
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="Slide {{ $index + 1 }}"
                                 class="w-full h-96 object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Navigation Arrows --}}
            <button type="button"
                    @click="currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>

            <button type="button"
                    @click="currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            {{-- Dots --}}
            <div class="flex justify-center gap-2 mt-6">
                @foreach($gallery as $index => $image)
                    <button type="button"
                            @click="currentSlide = {{ $index }}"
                            :class="{ 'bg-primary': currentSlide === {{ $index }}, 'bg-gray-300': currentSlide !== {{ $index }} }"
                            class="w-3 h-3 rounded-full transition-colors"></button>
                @endforeach
            </div>
        </div>
    @endif
</div>
