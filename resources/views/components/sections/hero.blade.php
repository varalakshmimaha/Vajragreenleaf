{{-- Hero Section Layout --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
@endphp

<div class="min-h-[60vh] flex items-center">
    <div class="w-full {{ $contentClasses }}">
        {{-- Subtitle (above title) --}}
        @if($section->subtitle)
            <p class="text-lg md:text-xl text-primary font-semibold mb-4 uppercase tracking-wider">
                {{ $section->subtitle }}
            </p>
        @endif

        {{-- Title --}}
        @if($section->title)
            <h1 class="{{ $titleClasses }} text-5xl md:text-6xl lg:text-7xl mb-6">
                {{ $section->title }}
            </h1>
        @endif

        {{-- Description --}}
        @if($section->description)
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl {{ $section->content_alignment === 'center' ? 'mx-auto' : '' }} mb-8">
                {{ $section->description }}
            </p>
        @endif

        {{-- Rich Content --}}
        @if($section->content)
            <div class="prose prose-lg max-w-3xl {{ $section->content_alignment === 'center' ? 'mx-auto' : '' }} mb-8">
                {!! $section->content !!}
            </div>
        @endif

        {{-- Buttons --}}
        @if($section->button_text || $section->secondary_button_text)
            <div class="flex flex-wrap gap-4 {{ $section->content_alignment === 'center' ? 'justify-center' : ($section->content_alignment === 'right' ? 'justify-end' : 'justify-start') }}">
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
</div>
