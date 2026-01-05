{{-- Default Section Layout - Text with optional image --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $imagePosition = $section->image_position ?? 'left';
    $hasImage = $section->image && $imagePosition !== 'background' && $imagePosition !== 'none';
@endphp

<div class="{{ $hasImage ? 'grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center' : '' }}">
    {{-- Image (Left or Top) --}}
    @if($hasImage && in_array($imagePosition, ['left', 'top']))
        <div class="{{ $imagePosition === 'top' ? 'lg:col-span-2' : '' }}">
            <img src="{{ asset('storage/' . $section->image) }}"
                 alt="{{ $section->title }}"
                 class="w-full rounded-lg shadow-lg {{ $imagePosition === 'top' ? 'max-h-96 object-cover' : '' }}">
        </div>
    @endif

    {{-- Content --}}
    <div class="{{ !$hasImage || $imagePosition === 'top' || $imagePosition === 'bottom' ? 'lg:col-span-2' : '' }}">
        {{-- Title --}}
        @if($section->title)
            <h2 class="{{ $titleClasses }} mb-4">
                {{ $section->title }}
            </h2>
        @endif

        {{-- Subtitle --}}
        @if($section->subtitle)
            <p class="text-xl text-gray-600 mb-6 {{ $contentClasses }}">
                {{ $section->subtitle }}
            </p>
        @endif

        {{-- Description --}}
        @if($section->description)
            <p class="text-gray-600 mb-6 {{ $contentClasses }}">
                {{ $section->description }}
            </p>
        @endif

        {{-- Rich Content --}}
        @if($section->content)
            <div class="prose max-w-none {{ $contentClasses }}">
                {!! $section->content !!}
            </div>
        @endif

        {{-- Buttons --}}
        @if($section->button_text || $section->secondary_button_text)
            <div class="flex flex-wrap gap-4 mt-8 {{ $section->content_alignment === 'center' ? 'justify-center' : ($section->content_alignment === 'right' ? 'justify-end' : 'justify-start') }}">
                @if($section->button_text)
                    @include('components.sections.partials.button', [
                        'text' => $section->button_text,
                        'url' => $section->button_url,
                        'style' => $section->button_style,
                        'size' => $section->button_size
                    ])
                @endif

                @if($section->secondary_button_text)
                    @include('components.sections.partials.button', [
                        'text' => $section->secondary_button_text,
                        'url' => $section->secondary_button_url,
                        'style' => 'outline',
                        'size' => $section->button_size
                    ])
                @endif
            </div>
        @endif
    </div>

    {{-- Image (Right or Bottom) --}}
    @if($hasImage && in_array($imagePosition, ['right', 'bottom']))
        <div class="{{ $imagePosition === 'bottom' ? 'lg:col-span-2' : '' }}">
            <img src="{{ asset('storage/' . $section->image) }}"
                 alt="{{ $section->title }}"
                 class="w-full rounded-lg shadow-lg {{ $imagePosition === 'bottom' ? 'max-h-96 object-cover' : '' }}">
        </div>
    @endif
</div>
