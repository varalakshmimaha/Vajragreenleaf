{{-- Tabs Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $items = $section->items ?? [];
    $sectionId = 'tabs-' . ($section->id ?? uniqid());
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

    {{-- Tabs --}}
    @if(count($items) > 0)
        <div x-data="{ activeTab: 0 }">
            {{-- Tab Buttons --}}
            <div class="flex flex-wrap gap-2 border-b border-gray-200 mb-6">
                @foreach($items as $index => $item)
                    <button type="button"
                            @click="activeTab = {{ $index }}"
                            :class="{ 'border-primary text-primary': activeTab === {{ $index }}, 'border-transparent text-gray-600 hover:text-gray-900': activeTab !== {{ $index }} }"
                            class="px-6 py-3 font-medium border-b-2 transition-colors -mb-px">
                        @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }} mr-2"></i>
                        @endif
                        {{ $item['title'] ?? 'Tab ' . ($index + 1) }}
                    </button>
                @endforeach
            </div>

            {{-- Tab Content --}}
            @foreach($items as $index => $item)
                <div x-show="activeTab === {{ $index }}"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="prose max-w-none">
                    {!! $item['content'] ?? '' !!}

                    @if(!empty($item['image']))
                        <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['title'] ?? '' }}" class="mt-4 rounded-lg">
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
