@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200';
    
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary/90 focus:ring-primary',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
        'outline' => 'border-primary text-primary hover:bg-primary/10 focus:ring-primary',
        'white' => 'bg-white text-primary hover:bg-gray-50 focus:ring-primary',
    ];
    
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
