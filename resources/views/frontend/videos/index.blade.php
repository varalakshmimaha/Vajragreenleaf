@extends('layouts.frontend')

@section('title', 'Video Gallery')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-[#052d00] text-white py-24 overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Video Gallery</h1>
            <p class="text-xl text-emerald-100/80 max-w-2xl mx-auto leading-relaxed">
                Explore our collection of product demos, testimonials, event highlights, and more.
            </p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-8 rounded-full"></div>
        </div>
    </section>

    <!-- Filters & Gallery -->
    <section class="py-20 bg-white" x-data="{ activeFilter: '{{ request('category', '') }}' }">
        <div class="container mx-auto px-4">
            
            <!-- Category Filter -->
            @if(count($categories) > 0)
                <div class="flex flex-wrap justify-center gap-4 mb-16">
                    <a href="{{ route('videos.index') }}" 
                       class="px-8 py-3 rounded-full font-bold transition-all border-2"
                       :class="activeFilter === '' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-100' : 'border-gray-200 text-gray-600 hover:border-emerald-600 hover:text-emerald-600'">
                        All Videos
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('videos.index', ['category' => $cat]) }}" 
                           class="px-8 py-3 rounded-full font-bold transition-all border-2"
                           :class="activeFilter === '{{ $cat }}' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-100' : 'border-gray-200 text-gray-600 hover:border-emerald-600 hover:text-emerald-600'">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Videos Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($videos as $video)
                    <div class="group" x-data="{ playing: false }">
                        <div class="relative aspect-video rounded-3xl overflow-hidden mb-6 bg-gray-100 shadow-md group-hover:shadow-xl transition-all duration-300">
                            <!-- Thumbnail / Overlay -->
                            <div x-show="!playing" class="absolute inset-0 z-10">
                                @if($video->thumbnail)
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-video text-gray-300 text-4xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                                    <button @click="playing = true" class="w-20 h-20 bg-white text-emerald-600 rounded-full flex items-center justify-center text-3xl shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-play ml-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Video Player -->
                            <div x-show="playing" class="w-full h-full" x-cloak>
                                @if($video->type === 'youtube')
                                    @php
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->url, $match);
                                        $youtubeId = $match[1] ?? null;
                                    @endphp
                                    <iframe :src="playing ? 'https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1' : ''" class="w-full h-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                @elseif($video->type === 'vimeo')
                                    @php
                                        preg_match('/(https?:\/\/)?(www\.)?(vimeo\.com\/)?(\d+)/', $video->url, $match);
                                        $vimeoId = $match[4] ?? null;
                                    @endphp
                                    <iframe :src="playing ? 'https://player.vimeo.com/video/{{ $vimeoId }}?autoplay=1' : ''" class="w-full h-full" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                @elseif($video->type === 'upload')
                                    <video controls class="w-full h-full" :autoplay="playing">
                                        <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        </div>

                        <div class="px-2">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-emerald-600 text-xs font-bold uppercase tracking-wider">{{ $video->category ?? 'General' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300 mb-2">{{ $video->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">{{ $video->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4 text-gray-300 text-4xl">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">No videos found.</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
