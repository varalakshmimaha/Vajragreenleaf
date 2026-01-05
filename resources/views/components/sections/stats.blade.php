{{-- Statistics/Counters Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $gridClasses = $section->getGridColumnsClass();
    $items = $section->items ?? [];
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

    {{-- Stats Grid --}}
    @if(count($items) > 0)
        <div class="grid {{ $gridClasses }} gap-8">
            @foreach($items as $item)
                <div class="text-center">
                    @if(!empty($item['icon']))
                        <i class="{{ $item['icon'] }} text-4xl text-primary mb-4"></i>
                    @endif

                    <div class="text-4xl md:text-5xl font-bold text-primary mb-2" data-counter="{{ $item['title'] ?? '0' }}">
                        {{ $item['title'] ?? '0' }}
                    </div>

                    @if(!empty($item['content']))
                        <p class="text-gray-600">{{ $item['content'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
