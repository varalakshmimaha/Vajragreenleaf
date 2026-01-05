@php
    $title = $data['title'] ?? 'Meet Our Team';
    $subtitle = $data['subtitle'] ?? 'Our Experts';
    $team = $data['team'] ?? collect();
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="animate-fade-in-up">
            @if($subtitle)
                <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <div class="w-20 h-1 gradient-primary mx-auto rounded-full"></div>
        </div>

        <!-- Team Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($team as $index => $member)
                <div class="group" data-animate="animate-fade-in-up" data-delay="{{ $index * 100 }}">
                    <div class="relative overflow-hidden rounded-2xl mb-4">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full aspect-square object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full aspect-square gradient-primary flex items-center justify-center text-white text-4xl font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        @endif

                        <!-- Social Links Overlay -->
                        <div class="absolute inset-0 gradient-primary opacity-0 group-hover:opacity-90 transition-opacity duration-300 flex items-center justify-center">
                            <div class="flex space-x-3">
                                @if($member->social_links)
                                    @foreach($member->social_links as $platform => $url)
                                        @if($url)
                                            <a href="{{ $url }}" target="_blank" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white hover:text-primary transition-colors">
                                                @switch($platform)
                                                    @case('facebook')
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4Z"/></svg>
                                                        @break
                                                    @case('twitter')
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953,4.57a10,10,0,0,1-2.825.775,4.958,4.958,0,0,0,2.163-2.723,9.99,9.99,0,0,1-3.127,1.195,4.92,4.92,0,0,0-8.384,4.482A13.978,13.978,0,0,1,1.671,3.149,4.93,4.93,0,0,0,3.195,9.723,4.9,4.9,0,0,1,.964,9.1v.062a4.923,4.923,0,0,0,3.946,4.827,4.9,4.9,0,0,1-2.212.084,4.935,4.935,0,0,0,4.6,3.417A9.868,9.868,0,0,1,0,19.54a13.94,13.94,0,0,0,7.548,2.212A13.9,13.9,0,0,0,21.543,7.82c0-.21,0-.42-.015-.63A10.025,10.025,0,0,0,24,4.59l-.047-.02Z"/></svg>
                                                        @break
                                                    @case('linkedin')
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447,20.452H16.893V14.883c0-1.328-.027-3.037-1.852-3.037-1.853,0-2.136,1.445-2.136,2.939v5.667H9.351V9h3.414v1.561h.046a3.745,3.745,0,0,1,3.37-1.85c3.6,0,4.267,2.37,4.267,5.455v6.286ZM5.337,7.433A2.064,2.064,0,1,1,7.4,5.368,2.062,2.062,0,0,1,5.337,7.433ZM7.119,20.452H3.555V9H7.119ZM22.225,0H1.771A1.75,1.75,0,0,0,0,1.729V22.271A1.749,1.749,0,0,0,1.771,24H22.222A1.756,1.756,0,0,0,24,22.271V1.729A1.756,1.756,0,0,0,22.222,0Z"/></svg>
                                                        @break
                                                    @case('github')
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12,0A12,12,0,0,0,8.2,23.4c.6.1.8-.3.8-.6V20.9c-3.3.7-4-1.6-4-1.6a3.2,3.2,0,0,0-1.3-1.7c-1-.7.1-.7.1-.7a2.5,2.5,0,0,1,1.8,1.2,2.5,2.5,0,0,0,3.4,1,2.5,2.5,0,0,1,.7-1.6c-2.6-.3-5.3-1.3-5.3-5.8a4.5,4.5,0,0,1,1.2-3.1,4.2,4.2,0,0,1,.1-3.1s1-.3,3.3,1.2a11.3,11.3,0,0,1,6,0c2.3-1.5,3.3-1.2,3.3-1.2a4.2,4.2,0,0,1,.1,3.1,4.5,4.5,0,0,1,1.2,3.1c0,4.5-2.7,5.5-5.3,5.8a2.8,2.8,0,0,1,.8,2.2v3.2c0,.3.2.7.8.6A12,12,0,0,0,12,0Z"/></svg>
                                                        @break
                                                @endswitch
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member->name }}</h3>
                        <p class="text-primary font-medium">{{ $member->designation }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
