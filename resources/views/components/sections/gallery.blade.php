{{-- Gallery Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $gridClasses = $section->getGridColumnsClass();
    $gallery = $section->gallery ?? [];
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

            @if($section->description)
                <p class="text-gray-600 max-w-3xl {{ $section->content_alignment === 'center' ? 'mx-auto' : '' }}">
                    {{ $section->description }}
                </p>
            @endif
        </div>
    @endif

    {{-- Gallery Grid --}}
    @if(count($gallery) > 0)
        <div class="grid {{ $gridClasses }} gap-4" x-data="{ lightboxOpen: false, currentImage: '' }">
            @foreach($gallery as $index => $image)
                <div class="relative group cursor-pointer overflow-hidden rounded-lg"
                     @click="lightboxOpen = true; currentImage = '{{ asset('storage/' . $image) }}'">
                    <img src="{{ asset('storage/' . $image) }}"
                         alt="Gallery image {{ $index + 1 }}"
                         class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl"></i>
                    </div>
                </div>
            @endforeach

            {{-- Lightbox --}}
            <div x-show="lightboxOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="lightboxOpen = false"
                 @keydown.escape.window="lightboxOpen = false"
                 class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                 style="display: none;">
                <img :src="currentImage" class="max-w-full max-h-[90vh] object-contain">
                <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white text-3xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
</div>
