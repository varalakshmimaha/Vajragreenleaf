@php
    $sectionId = $section->custom_id ?: 'section-' . $section->id;
    $sectionClasses = $section->getSectionClasses();
    $containerClasses = $section->getContainerClasses();
    $animationClasses = $section->getAnimationClasses();
    $bgStyle = $section->getBackgroundStyle();
@endphp

<section
    id="{{ $sectionId }}"
    class="{{ $sectionClasses }} {{ $animationClasses }} relative overflow-hidden"
    style="{{ $bgStyle }}"
    @if($section->animation_type !== 'none')
        data-delay="{{ $section->animation_delay }}"
    @endif
>
    {{-- Background Overlay --}}
    @if($section->background_overlay)
        <div class="absolute inset-0" style="background-color: {{ $section->background_overlay }};"></div>
    @endif

    {{-- Background Image (if position is background) --}}
    @if($section->image_position === 'background' && $section->image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $section->image) }}" alt="" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="{{ $containerClasses }} relative z-10">
        @switch($section->layout)
            @case('hero')
                @include('components.sections.hero', ['section' => $section])
                @break

            @case('cta')
                @include('components.sections.cta', ['section' => $section])
                @break

            @case('features')
                @include('components.sections.features', ['section' => $section])
                @break

            @case('cards')
                @include('components.sections.cards', ['section' => $section])
                @break

            @case('grid')
                @include('components.sections.grid', ['section' => $section])
                @break

            @case('gallery')
                @include('components.sections.gallery', ['section' => $section])
                @break

            @case('testimonials')
                @include('components.sections.testimonials', ['section' => $section])
                @break

            @case('stats')
                @include('components.sections.stats', ['section' => $section])
                @break

            @case('accordion')
                @include('components.sections.accordion', ['section' => $section])
                @break

            @case('tabs')
                @include('components.sections.tabs', ['section' => $section])
                @break

            @case('timeline')
                @include('components.sections.timeline', ['section' => $section])
                @break

            @case('team')
                @include('components.sections.team', ['section' => $section])
                @break

            @case('pricing')
                @include('components.sections.pricing', ['section' => $section])
                @break

            @case('contact')
                @include('components.sections.contact', ['section' => $section])
                @break

            @case('slider')
                @include('components.sections.slider', ['section' => $section])
                @break

            @case('custom')
                @include('components.sections.custom', ['section' => $section])
                @break

            @default
                @include('components.sections.default', ['section' => $section])
        @endswitch
    </div>

    {{-- Custom CSS --}}
    @if($section->custom_css)
        <style>
            {!! $section->custom_css !!}
        </style>
    @endif

    {{-- Custom JS --}}
    @if($section->custom_js)
        <script>
            {!! $section->custom_js !!}
        </script>
    @endif
</section>
