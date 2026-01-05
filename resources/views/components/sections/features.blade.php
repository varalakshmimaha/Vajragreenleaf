{{-- Features/Cards Grid Layout --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
    $gridClasses = $section->getGridColumnsClass();
    $items = $section->items ?? [];

    // Card classes
    $cardBaseClasses = 'p-6 transition-all duration-300';
    $cardStyleClasses = match($section->card_style) {
        'bordered' => 'border-2 border-gray-200',
        'shadow' => 'shadow-lg',
        'minimal' => '',
        'gradient' => 'bg-gradient-to-br from-white to-gray-50',
        default => 'bg-white shadow-md',
    };
    $cardHoverClasses = match($section->card_hover) {
        'lift' => 'card-hover-lift',
        'scale' => 'card-hover-scale',
        'glow' => 'card-hover-glow',
        'border' => 'card-hover-border',
        default => '',
    };
    $cardRoundedClasses = $section->card_rounded ? 'rounded-xl' : '';
@endphp

<div>
    {{-- Header --}}
    @if($section->title || $section->subtitle || $section->description)
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

    {{-- Features Grid --}}
    @if(count($items) > 0)
        <div class="grid {{ $gridClasses }} gap-6">
            @foreach($items as $index => $item)
                <div class="{{ $cardBaseClasses }} {{ $cardStyleClasses }} {{ $cardHoverClasses }} {{ $cardRoundedClasses }}"
                     style="animation-delay: {{ $index * 100 }}ms">

                    {{-- Icon --}}
                    @if(!empty($item['icon']))
                        <div class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <i class="{{ $item['icon'] }} text-2xl text-primary"></i>
                        </div>
                    @endif

                    {{-- Image --}}
                    @if(!empty($item['image']))
                        <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['title'] ?? '' }}"
                             class="w-full h-48 object-cover {{ $cardRoundedClasses }} mb-4">
                    @endif

                    {{-- Title --}}
                    @if(!empty($item['title']))
                        <h3 class="text-xl font-bold mb-3">
                            @if(!empty($item['url']))
                                <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors">
                                    {{ $item['title'] }}
                                </a>
                            @else
                                {{ $item['title'] }}
                            @endif
                        </h3>
                    @endif

                    {{-- Content --}}
                    @if(!empty($item['content']))
                        <p class="text-gray-600">
                            {{ $item['content'] }}
                        </p>
                    @endif

                    {{-- Link --}}
                    @if(!empty($item['url']) && empty($item['title']))
                        <a href="{{ $item['url'] }}" class="inline-flex items-center text-primary hover:underline mt-4">
                            Learn More <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Buttons --}}
    @if($section->button_text)
        <div class="mt-12 {{ $section->content_alignment === 'center' ? 'text-center' : ($section->content_alignment === 'right' ? 'text-right' : 'text-left') }}">
            @include('components.sections.partials.button', [
                'text' => $section->button_text,
                'url' => $section->button_url,
                'style' => $section->button_style,
                'size' => $section->button_size
            ])
        </div>
    @endif
</div>
