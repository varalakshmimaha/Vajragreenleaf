@extends('layouts.frontend')

@section('title', 'Gallery')

@push('styles')
<style>
    /* Masonry Grid */
    .masonry-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .masonry-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .masonry-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .masonry-item {
        break-inside: avoid;
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }

    .masonry-item.tall {
        grid-row: span 2;
    }

    /* Lightbox */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .lightbox.active {
        opacity: 1;
        visibility: visible;
    }

    .lightbox img {
        max-width: 90vw;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .lightbox.active img {
        transform: scale(1);
    }

    .lightbox-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: transform 0.3s ease;
        z-index: 10;
    }

    .lightbox-close:hover {
        transform: scale(1.2);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 1rem;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .lightbox-nav:hover {
        background: rgba(255,255,255,0.2);
    }

    .lightbox-prev { left: 1.5rem; }
    .lightbox-next { right: 1.5rem; }

    .lightbox-info {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        text-align: center;
        max-width: 90%;
    }

    /* Gallery Item Animations */
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        cursor: pointer;
        background: #f3f4f6;
    }

    .gallery-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }

    .gallery-item:hover::before {
        opacity: 1;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-item-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.5rem;
        z-index: 2;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .gallery-item:hover .gallery-item-overlay {
        transform: translateY(0);
    }

    .gallery-zoom-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 2;
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .gallery-item:hover .gallery-zoom-icon {
        transform: translate(-50%, -50%) scale(1);
    }

    /* Featured Slider */
    .featured-slider {
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
    }

    .featured-slides {
        display: flex;
        transition: transform 0.5s ease;
    }

    .featured-slide {
        min-width: 100%;
        position: relative;
    }

    .featured-slide img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .featured-slide img {
            height: 300px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white" data-animate="animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Our Gallery</h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">Explore moments, achievements, and memories captured through our lens.</p>
            </div>
        </div>
    </section>

    <!-- Featured Images Slider -->
    @if($featuredImages->count() > 0)
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="featured-slider" data-animate="animate-fade-in">
                    <div class="featured-slides" id="featured-slides">
                        @foreach($featuredImages as $image)
                            <div class="featured-slide">
                                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                                    <h3 class="text-2xl md:text-3xl font-bold mb-2 font-heading">{{ $image->title }}</h3>
                                    @if($image->description)
                                        <p class="text-white/80 max-w-xl">{{ Str::limit($image->description, 120) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($featuredImages->count() > 1)
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all z-10" onclick="slideFeature(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all z-10" onclick="slideFeature(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>

                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                            @foreach($featuredImages as $index => $image)
                                <button class="featured-dot w-3 h-3 rounded-full transition-all {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}"
                                    onclick="goToSlide({{ $index }})"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Category Filter -->
    @if($categories->count() > 0)
        <section class="py-8 bg-white border-b sticky top-16 z-40">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('gallery.index') }}"
                       class="px-5 py-2.5 rounded-full transition-all duration-300 font-medium {{ !$activeCategory ? 'bg-primary text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-th mr-2"></i>All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('gallery.category', $category->slug) }}"
                           class="px-5 py-2.5 rounded-full transition-all duration-300 font-medium {{ $activeCategory == $category->slug ? 'bg-primary text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->name }}
                            <span class="ml-1 text-xs opacity-70">({{ $category->active_galleries_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Gallery Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            @if($galleries->count() > 0)
                <div class="masonry-grid">
                    @foreach($galleries as $index => $image)
                        <div class="gallery-item {{ $index % 5 === 0 ? 'tall' : '' }} aspect-square {{ $index % 5 === 0 ? 'aspect-auto' : '' }}"
                             data-animate="animate-fade-in-up"
                             data-index="{{ $index }}"
                             onclick="openLightbox({{ $index }})">
                            <img src="{{ asset('storage/' . $image->image) }}"
                                 alt="{{ $image->title }}"
                                 loading="lazy"
                                 class="{{ $index % 5 === 0 ? 'h-full' : '' }}">

                            <div class="gallery-zoom-icon text-primary">
                                <i class="fas fa-search-plus text-xl"></i>
                            </div>

                            <div class="gallery-item-overlay text-white">
                                <h4 class="font-bold text-lg mb-1">{{ $image->title }}</h4>
                                @if($image->category)
                                    <span class="text-sm text-white/70">{{ $image->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($galleries->hasPages())
                    <div class="mt-12">
                        {{ $galleries->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-20 text-gray-500">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-images text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No images found</h3>
                    <p class="text-gray-500">Check back later for new photos!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-heading" data-animate="animate-fade-in">Want to Be Part of Our Story?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-animate="animate-fade-in">Join our team and create memorable moments together.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-animate="animate-fade-in-up">
                <a href="{{ route('careers.index') }}" class="btn-primary text-lg">
                    <i class="fas fa-briefcase mr-2"></i>View Careers
                </a>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-gray-900 transition-all">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        <img id="lightbox-image" src="" alt="">
        <div class="lightbox-info">
            <h3 id="lightbox-title" class="text-xl font-bold mb-1"></h3>
            <p id="lightbox-description" class="text-white/70"></p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Gallery data
    const galleryItems = @json($galleries->items());
    let currentIndex = 0;
    let currentSlide = 0;
    const totalSlides = {{ $featuredImages->count() }};

    // Lightbox functions
    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    function updateLightbox() {
        const item = galleryItems[currentIndex];
        document.getElementById('lightbox-image').src = '/storage/' + item.image;
        document.getElementById('lightbox-title').textContent = item.title;
        document.getElementById('lightbox-description').textContent = item.description || '';
    }

    function navigateLightbox(direction) {
        currentIndex = (currentIndex + direction + galleryItems.length) % galleryItems.length;
        updateLightbox();
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('active')) return;

        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Click outside to close
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });

    // Featured slider
    function slideFeature(direction) {
        currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
        goToSlide(currentSlide);
    }

    function goToSlide(index) {
        currentSlide = index;
        document.getElementById('featured-slides').style.transform = `translateX(-${index * 100}%)`;

        // Update dots
        document.querySelectorAll('.featured-dot').forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('bg-white', 'w-8');
                dot.classList.remove('bg-white/50');
            } else {
                dot.classList.remove('bg-white', 'w-8');
                dot.classList.add('bg-white/50');
            }
        });
    }

    // Auto-slide featured images
    @if($featuredImages->count() > 1)
    setInterval(() => {
        slideFeature(1);
    }, 5000);
    @endif
</script>
@endpush
