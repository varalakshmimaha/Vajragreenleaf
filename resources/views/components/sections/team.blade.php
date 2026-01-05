{{-- Team Section --}}
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

    {{-- Team Grid --}}
    @if(count($items) > 0)
        <div class="grid {{ $gridClasses }} gap-8">
            @foreach($items as $item)
                <div class="text-center group">
                    {{-- Photo --}}
                    <div class="relative mb-4 overflow-hidden rounded-xl">
                        @if(!empty($item['image']))
                            <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['title'] ?? 'Team member' }}"
                                 class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-6xl text-gray-400"></i>
                            </div>
                        @endif

                        {{-- Social overlay --}}
                        @if(!empty($item['url']))
                            <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ $item['url'] }}" class="text-white text-2xl hover:scale-110 transition-transform">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Name --}}
                    <h3 class="text-xl font-bold">{{ $item['title'] ?? 'Team Member' }}</h3>

                    {{-- Position (using icon field) --}}
                    @if(!empty($item['icon']))
                        <p class="text-primary">{{ $item['icon'] }}</p>
                    @endif

                    {{-- Bio --}}
                    @if(!empty($item['content']))
                        <p class="text-gray-600 mt-2">{{ $item['content'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
