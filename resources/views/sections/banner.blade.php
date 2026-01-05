@php $banners = $data['banners'] ?? collect(); @endphp

@if($banners->count() > 0)
<section class="relative min-h-[80vh] flex items-center overflow-hidden">
    @foreach($banners as $index => $banner)
        <div class="banner-slide absolute inset-0 {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
            @if($banner->isVideo() && $banner->video)
                <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
                    <source src="{{ asset('storage/' . $banner->video) }}" type="video/mp4">
                </video>
            @elseif($banner->image)
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $banner->image) }}')"></div>
            @else
                <div class="absolute inset-0 gradient-primary"></div>
            @endif

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50"></div>

            <!-- Content -->
            <div class="relative container mx-auto px-4 h-full flex items-center">
                <div class="max-w-3xl text-white">
                    @if($banner->subtitle)
                        <p class="text-primary-light text-lg md:text-xl mb-4 animate-fade-in-down" style="animation-delay: 0.2s">{{ $banner->subtitle }}</p>
                    @endif

                    @if($banner->title)
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight animate-fade-in-up">{{ $banner->title }}</h1>
                    @endif

                    @if($banner->description)
                        <p class="text-lg md:text-xl text-gray-200 mb-8 animate-fade-in-up" style="animation-delay: 0.3s">{{ $banner->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.4s">
                        @if($banner->cta_text && $banner->cta_url)
                            <a href="{{ $banner->cta_url }}" class="gradient-primary text-white px-8 py-4 rounded-full font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300">
                                {{ $banner->cta_text }}
                            </a>
                        @endif

                        @if($banner->cta_text_2 && $banner->cta_url_2)
                            <a href="{{ $banner->cta_url_2 }}" class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full font-semibold border border-white/30 hover:bg-white/20 transition-all duration-300">
                                {{ $banner->cta_text_2 }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if($banners->count() > 1)
        <!-- Slider Navigation -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-10">
            @foreach($banners as $index => $banner)
                <button class="banner-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors {{ $index === 0 ? 'bg-white' : '' }}" data-index="{{ $index }}"></button>
            @endforeach
        </div>
    @endif
</section>

@if($banners->count() > 1)
<script>
    (function() {
        const slides = document.querySelectorAll('.banner-slide');
        const dots = document.querySelectorAll('.banner-dot');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('bg-white'));
            slides[index].classList.add('active');
            dots[index].classList.add('bg-white');
            currentIndex = index;
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showSlide(index));
        });

        setInterval(() => {
            showSlide((currentIndex + 1) % slides.length);
        }, 6000);
    })();
</script>
<style>
    .banner-slide { opacity: 0; transition: opacity 1s ease-in-out; }
    .banner-slide.active { opacity: 1; z-index: 1; }
</style>
@endif
@endif
