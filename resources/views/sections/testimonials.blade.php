@php
    $title = $data['title'] ?? 'What Our Clients Say';
    $subtitle = $data['subtitle'] ?? 'Testimonials';
    $testimonials = $data['testimonials'] ?? collect();
@endphp

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="animate-fade-in-up">
            @if($subtitle)
                <p class="text-primary font-semibold mb-3 uppercase tracking-wide">{{ $subtitle }}</p>
            @endif
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <div class="w-20 h-1 gradient-primary mx-auto rounded-full"></div>
        </div>

        <!-- Testimonials Slider -->
        <div class="relative overflow-hidden" id="testimonials-slider">
            <div class="flex transition-transform duration-500" id="testimonials-track">
                @foreach($testimonials as $testimonial)
                    <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                        <div class="bg-gray-50 rounded-2xl p-8 h-full hover-lift">
                            <!-- Rating -->
                            <div class="flex space-x-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>

                            <!-- Quote -->
                            <p class="text-gray-600 mb-6 italic">"{{ $testimonial->review }}"</p>

                            <!-- Author -->
                            <div class="flex items-center">
                                @if($testimonial->photo)
                                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->client_name }}" class="w-12 h-12 rounded-full object-cover mr-4">
                                @else
                                    <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-bold mr-4">
                                        {{ substr($testimonial->client_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $testimonial->client_name }}</h4>
                                    @if($testimonial->designation || $testimonial->company_name)
                                        <p class="text-sm text-gray-500">
                                            {{ $testimonial->designation }}
                                            @if($testimonial->designation && $testimonial->company_name), @endif
                                            {{ $testimonial->company_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation -->
            @if($testimonials->count() > 3)
                <button class="absolute left-0 top-1/2 -translate-y-1/2 w-12 h-12 gradient-primary rounded-full flex items-center justify-center text-white shadow-lg hover:scale-110 transition-transform" id="prev-testimonial">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="absolute right-0 top-1/2 -translate-y-1/2 w-12 h-12 gradient-primary rounded-full flex items-center justify-center text-white shadow-lg hover:scale-110 transition-transform" id="next-testimonial">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endif
        </div>
    </div>
</section>

@if($testimonials->count() > 3)
<script>
    (function() {
        const track = document.getElementById('testimonials-track');
        const prev = document.getElementById('prev-testimonial');
        const next = document.getElementById('next-testimonial');
        let position = 0;
        const slideWidth = 33.333;
        const maxPosition = {{ $testimonials->count() }} - 3;

        function updatePosition() {
            track.style.transform = `translateX(-${position * slideWidth}%)`;
        }

        prev.addEventListener('click', () => {
            position = Math.max(0, position - 1);
            updatePosition();
        });

        next.addEventListener('click', () => {
            position = Math.min(maxPosition, position + 1);
            updatePosition();
        });
    })();
</script>
@endif
