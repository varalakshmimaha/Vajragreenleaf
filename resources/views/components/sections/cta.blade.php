{{-- Call to Action Section --}}
@php
    $titleClasses = $section->getTitleClasses();
@endphp

<div class="text-center py-8">
    {{-- Title --}}
    @if($section->title)
        <h2 class="{{ $titleClasses }} mb-4">
            {{ $section->title }}
        </h2>
    @endif

    {{-- Subtitle --}}
    @if($section->subtitle)
        <p class="text-xl mb-4">
            {{ $section->subtitle }}
        </p>
    @endif

    {{-- Description --}}
    @if($section->description)
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
            {{ $section->description }}
        </p>
    @endif

    {{-- Buttons --}}
    @if($section->button_text || $section->secondary_button_text)
        <div class="flex flex-wrap gap-4 justify-center">
            @if($section->button_text)
                @include('components.sections.partials.button', [
                    'text' => $section->button_text,
                    'url' => $section->button_url,
                    'style' => $section->button_style,
                    'size' => 'lg'
                ])
            @endif

            @if($section->secondary_button_text)
                @include('components.sections.partials.button', [
                    'text' => $section->secondary_button_text,
                    'url' => $section->secondary_button_url,
                    'style' => 'outline',
                    'size' => 'lg'
                ])
            @endif
        </div>
    @endif
</div>
