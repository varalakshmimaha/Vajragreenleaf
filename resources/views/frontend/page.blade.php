@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')
    <!-- Page Header -->
    <section class="gradient-primary py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/></svg>'); background-size: 50px 50px;"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4" data-animate="animate-fade-in-up">{{ $page->title }}</h1>
                <nav class="text-white/80" data-animate="animate-fade-in-up" data-delay="200">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <span>{{ $page->title }}</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Page Content -->
    @foreach($sections as $sectionData)
        @if($sectionData['type'] === 'old')
            {{-- Old section types (banner, services, portfolio, etc.) --}}
            @php
                $viewPath = $sectionData['view'];
                $data = $sectionData['data'];
                $pageSection = $sectionData['pageSection'];
            @endphp

            @if(View::exists($viewPath))
                @include($viewPath, ['section' => $pageSection, 'data' => $data])
            @endif
        @elseif($sectionData['type'] === 'new')
            {{-- New dynamic sections --}}
            @php
                $section = $sectionData['section'];
            @endphp
            <x-section-renderer :section="$section" />
        @endif
    @endforeach
@endsection
