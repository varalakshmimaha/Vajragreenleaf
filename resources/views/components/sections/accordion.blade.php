{{-- Accordion/FAQ Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $items = $section->items ?? [];
    $sectionId = 'accordion-' . ($section->id ?? uniqid());
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

    {{-- Accordion --}}
    @if(count($items) > 0)
        <div class="max-w-3xl {{ $section->content_alignment === 'center' ? 'mx-auto' : '' }}" x-data="{ openItem: null }">
            @foreach($items as $index => $item)
                <div class="border-b border-gray-200">
                    <button type="button"
                            @click="openItem = openItem === {{ $index }} ? null : {{ $index }}"
                            class="w-full py-4 flex items-center justify-between text-left hover:text-primary transition-colors">
                        <span class="font-semibold text-lg">{{ $item['title'] ?? 'Question ' . ($index + 1) }}</span>
                        <i class="fas fa-chevron-down transition-transform duration-300"
                           :class="{ 'rotate-180': openItem === {{ $index }} }"></i>
                    </button>

                    <div x-show="openItem === {{ $index }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="pb-4 text-gray-600"
                         style="display: none;">
                        {!! $item['content'] ?? '' !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
