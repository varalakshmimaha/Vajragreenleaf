@extends('layouts.frontend')

@section('title', 'Our Bankers')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-[#052d00] text-white py-24 overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Our Bankers</h1>
            <p class="text-xl text-emerald-100/80 max-w-2xl mx-auto leading-relaxed">
                We partner with leading financial institutions to ensure smooth and secure transactions for our stakeholders.
            </p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-8 rounded-full"></div>
        </div>
    </section>

    <!-- Bankers Grid -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse($bankers as $banker)
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 text-center flex flex-col items-center">
                        <div class="w-32 h-32 bg-gray-50 rounded-xl overflow-hidden mb-6 flex items-center justify-center p-4 group-hover:scale-105 transition-transform duration-500">
                            @if($banker->logo)
                                <img src="{{ asset('storage/' . $banker->logo) }}" alt="{{ $banker->name }}" class="max-w-full max-h-full object-contain">
                            @else
                                <i class="fas fa-university text-5xl text-gray-200"></i>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $banker->name }}</h3>
                        
                        @if($banker->description)
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2" title="{{ $banker->description }}">{{ $banker->description }}</p>
                        @endif

                        @if($banker->website_url)
                            <a href="{{ $banker->website_url }}" target="_blank" class="mt-auto text-emerald-600 font-bold text-sm hover:underline flex items-center group/link">
                                Visit Website 
                                <i class="fas fa-external-link-alt ml-2 text-xs transition-transform group-hover/link:translate-x-1 group-hover/link:-translate-y-1"></i>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4 text-gray-300 text-4xl">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">No bankers listed yet.</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
