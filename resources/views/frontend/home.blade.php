@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')
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
