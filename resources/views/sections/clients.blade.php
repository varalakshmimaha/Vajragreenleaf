@php
    $title = $data['title'] ?? 'Trusted By Leading Companies';
    $subtitle = $data['subtitle'] ?? 'Our Clients';
    $clients = $data['clients'] ?? collect();
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($title)
            <div class="text-center mb-12" data-animate="animate-fade-in-up">
                @if($subtitle)
                    <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
                @endif
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $title }}</h2>
            </div>
        @endif

        <!-- Client Logos -->
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12" data-animate="animate-fade-in-up">
            @foreach($clients as $client)
                <div class="grayscale hover:grayscale-0 opacity-60 hover:opacity-100 transition-all duration-300">
                    @if($client->website)
                        <a href="{{ $client->website }}" target="_blank" rel="noopener noreferrer">
                    @endif

                    @if($client->logo)
                        <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="h-12 md:h-16 w-auto object-contain">
                    @else
                        <span class="text-xl font-bold text-gray-400">{{ $client->name }}</span>
                    @endif

                    @if($client->website)
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
