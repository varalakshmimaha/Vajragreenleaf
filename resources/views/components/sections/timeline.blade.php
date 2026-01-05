{{-- Timeline Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
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

    {{-- Timeline --}}
    @if(count($items) > 0)
        <div class="relative max-w-4xl mx-auto">
            {{-- Vertical Line --}}
            <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-primary/20"></div>

            @foreach($items as $index => $item)
                <div class="relative flex items-center mb-8 {{ $index % 2 === 0 ? 'flex-row' : 'flex-row-reverse' }}">
                    {{-- Content --}}
                    <div class="w-5/12 {{ $index % 2 === 0 ? 'pr-8 text-right' : 'pl-8 text-left' }}">
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            @if(!empty($item['icon']))
                                <span class="text-sm text-primary font-semibold">{{ $item['icon'] }}</span>
                            @endif
                            <h3 class="text-xl font-bold mt-1">{{ $item['title'] ?? '' }}</h3>
                            <p class="text-gray-600 mt-2">{{ $item['content'] ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Center Dot --}}
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary rounded-full border-4 border-white shadow"></div>

                    {{-- Spacer --}}
                    <div class="w-5/12"></div>
                </div>
            @endforeach
        </div>
    @endif
</div>
