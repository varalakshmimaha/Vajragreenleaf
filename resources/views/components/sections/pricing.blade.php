{{-- Pricing Section --}}
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

            @if($section->description)
                <p class="text-gray-600 max-w-3xl {{ $section->content_alignment === 'center' ? 'mx-auto' : '' }}">
                    {{ $section->description }}
                </p>
            @endif
        </div>
    @endif

    {{-- Pricing Cards --}}
    @if(count($items) > 0)
        <div class="grid {{ $gridClasses }} gap-8">
            @foreach($items as $index => $item)
                @php
                    $isFeatured = !empty($item['url']) && str_contains($item['url'], 'featured');
                @endphp
                <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden {{ $isFeatured ? 'ring-2 ring-primary scale-105' : '' }}">
                    {{-- Featured Badge --}}
                    @if($isFeatured)
                        <div class="absolute top-0 right-0 bg-primary text-white px-4 py-1 text-sm font-semibold">
                            Popular
                        </div>
                    @endif

                    <div class="p-8">
                        {{-- Plan Name --}}
                        <h3 class="text-xl font-bold mb-2">{{ $item['title'] ?? 'Plan' }}</h3>

                        {{-- Price (using icon field) --}}
                        @if(!empty($item['icon']))
                            <div class="mb-6">
                                <span class="text-4xl font-bold">{{ $item['icon'] }}</span>
                                <span class="text-gray-500">/month</span>
                            </div>
                        @endif

                        {{-- Features (content as HTML list) --}}
                        @if(!empty($item['content']))
                            <div class="space-y-3 mb-8">
                                @foreach(explode("\n", $item['content']) as $feature)
                                    @if(trim($feature))
                                        <div class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-3"></i>
                                            <span>{{ trim($feature) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        {{-- CTA Button --}}
                        <a href="{{ $item['image'] ?? '#' }}"
                           class="block w-full py-3 px-6 text-center rounded-lg font-semibold transition-colors
                                  {{ $isFeatured ? 'bg-primary text-white hover:bg-primary/90' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                            Get Started
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
