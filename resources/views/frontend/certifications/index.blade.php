@extends('layouts.frontend')

@section('title', 'Certifications')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-[#052d00] text-white py-24 overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Our Certifications</h1>
            <p class="text-xl text-emerald-100/80 max-w-2xl mx-auto leading-relaxed">
                We maintain the highest standards of quality and excellence, backed by official certifications from leading authorities.
            </p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-8 rounded-full"></div>
        </div>
    </section>

    <!-- Certifications Grid -->
    <section class="py-20 bg-white" x-data="{ 
        open: false, 
        currentImg: '', 
        currentTitle: '',
        openModal(img, title) {
            this.currentImg = img;
            this.currentTitle = title;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.open = false;
            document.body.style.overflow = 'auto';
        }
    }">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($certifications as $cert)
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        <div class="relative aspect-[4/3] bg-gray-50 rounded-xl overflow-hidden mb-6 flex items-center justify-center p-4 group-hover:bg-emerald-50/50 transition-colors duration-500">
                            @if($cert->image)
                                <img src="{{ asset('storage/' . $cert->image) }}" 
                                     alt="{{ $cert->title }}" 
                                     class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-700 cursor-zoom-in" 
                                     @click="openModal('{{ asset('storage/' . $cert->image) }}', '{{ $cert->title }}')">
                            @else
                                <i class="fas fa-certificate text-6xl text-gray-200"></i>
                            @endif
                            
                            @if($cert->image)
                            <div class="absolute inset-0 bg-emerald-600/0 group-hover:bg-emerald-600/10 transition-all duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100 pointer-events-none">
                                <div class="bg-white text-emerald-600 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">{{ $cert->title }}</h3>
                        <p class="text-emerald-600 font-semibold text-sm mb-3">{{ $cert->issuing_authority }}</p>
                        
                        @if($cert->certificate_number)
                            <p class="text-gray-500 text-xs font-mono bg-gray-50 inline-block px-2 py-1 rounded mb-4">No: {{ $cert->certificate_number }}</p>
                        @endif
                        
                        @if($cert->description)
                            <p class="text-gray-600 text-sm line-clamp-3 mb-6 leading-relaxed">{{ $cert->description }}</p>
                        @endif

                        @if($cert->issue_date)
                            <div class="pt-4 border-t border-gray-100 flex justify-between text-xs text-gray-400">
                                <span class="flex items-center"><i class="far fa-calendar-alt mr-1.5"></i> Issued: {{ $cert->issue_date->format('M Y') }}</span>
                                @if($cert->expiry_date)
                                    <span class="flex items-center"><i class="far fa-clock mr-1.5"></i> Expires: {{ $cert->expiry_date->format('M Y') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4 text-gray-300 text-4xl">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">No certifications to display.</h3>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Global Lightbox Modal -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-10 bg-black/95 backdrop-blur-sm"
             @keydown.escape.window="closeModal()"
             @click.away="closeModal()"
             x-cloak>
            
            <div class="relative max-w-6xl w-full h-full flex flex-col items-center justify-center">
                <!-- Close Button -->
                <button @click="closeModal()" class="absolute -top-12 right-0 md:-right-12 text-white hover:text-emerald-400 text-4xl transition-colors z-[110] outline-none">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Header Info in Modal -->
                <div class="absolute -top-12 left-0 text-white text-left hidden md:block">
                    <h4 class="text-xl font-bold" x-text="currentTitle"></h4>
                </div>

                <!-- Image container with inner shadow/effect -->
                <div class="relative bg-white p-2 rounded-lg shadow-2xl max-h-full overflow-hidden flex items-center justify-center">
                    <img :src="currentImg" :alt="currentTitle" class="max-w-full max-h-[80vh] md:max-h-[85vh] object-contain rounded shadow-inner">
                </div>
                
                <p class="mt-4 text-gray-400 text-sm md:hidden" x-text="currentTitle"></p>
            </div>
        </div>
    </section>
@endsection
