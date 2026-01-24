@extends('layouts.frontend')

@section('title', 'Awards & Recognition')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-[#052d00] text-white py-24 overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Our Awards & Recognition</h1>
            <p class="text-xl text-emerald-100/80 max-w-2xl mx-auto leading-relaxed">
                Celebrating milestones and achievements that testify to our commitment to excellence and innovation.
            </p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-8 rounded-full"></div>
        </div>
    </section>

    <!-- Awards Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                @forelse($awards as $award)
                    <div class="bg-white rounded-3xl p-8 mb-12 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-10 hover:shadow-xl transition-all duration-300">
                        <div class="w-full md:w-1/3">
                            <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden p-6 flex items-center justify-center">
                                @if($award->image)
                                    <img src="{{ asset('storage/' . $award->image) }}" alt="{{ $award->title }}" class="max-w-full max-h-full object-contain">
                                @else
                                    <i class="fas fa-trophy text-8xl text-yellow-500"></i>
                                @endif
                            </div>
                        </div>
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                @if($award->year)
                                    <span class="px-4 py-1 bg-emerald-100 text-emerald-600 text-sm font-bold rounded-full">{{ $award->year }}</span>
                                @endif
                                @if($award->category)
                                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm font-bold rounded-full">{{ $award->category }}</span>
                                @endif
                            </div>
                            
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $award->title }}</h2>
                            <p class="text-lg text-emerald-600 font-semibold mb-6">By {{ $award->awarding_organization }}</p>
                            
                            @if($award->description)
                                <div class="prose prose-lg text-gray-600 max-w-none">
                                    {{ $award->description }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4 text-gray-300 text-4xl">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Achievements coming soon!</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
