{{-- Contact Section --}}
@php
    $titleClasses = $section->getTitleClasses();
    $contentClasses = $section->getContentAlignmentClasses();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
    {{-- Contact Info --}}
    <div>
        @if($section->title)
            <h2 class="{{ $titleClasses }} mb-4 text-left">
                {{ $section->title }}
            </h2>
        @endif

        @if($section->description)
            <p class="text-gray-600 mb-8">
                {{ $section->description }}
            </p>
        @endif

        {{-- Contact Details --}}
        @if($section->items && count($section->items) > 0)
            <div class="space-y-6">
                @foreach($section->items as $item)
                    <div class="flex items-start">
                        @if(!empty($item['icon']))
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="{{ $item['icon'] }} text-primary text-xl"></i>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h4 class="font-semibold">{{ $item['title'] ?? '' }}</h4>
                            <p class="text-gray-600">{{ $item['content'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Image --}}
        @if($section->image)
            <img src="{{ asset('storage/' . $section->image) }}"
                 alt="{{ $section->title }}"
                 class="mt-8 rounded-lg shadow-lg">
        @endif
    </div>

    {{-- Contact Form --}}
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h3 class="text-2xl font-bold mb-6">Send us a message</h3>

        <form action="{{ route('contact.submit') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="tel" name="phone"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                <input type="text" name="subject" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                <textarea name="message" rows="5" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                Send Message
            </button>
        </form>
    </div>
</div>
