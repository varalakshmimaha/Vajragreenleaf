{{-- Testimonials Section --}}
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

    {{-- Testimonials --}}
    @if(count($items) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($items as $item)
                <div class="bg-white rounded-xl shadow-lg p-6 relative">
                    {{-- Quote Icon --}}
                    <div class="absolute -top-4 left-6 w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-quote-left text-white"></i>
                    </div>

                    {{-- Content --}}
                    <p class="text-gray-600 mt-4 mb-6 italic">
                        "{{ $item['content'] ?? '' }}"
                    </p>

                    {{-- Author --}}
                    <div class="flex items-center">
                        @if(!empty($item['image']))
                            <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['title'] ?? 'Testimonial' }}"
                                 class="w-12 h-12 rounded-full object-cover mr-4">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold">{{ $item['title'] ?? 'Anonymous' }}</h4>
                            @if(!empty($item['icon']))
                                <p class="text-sm text-gray-500">{{ $item['icon'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
