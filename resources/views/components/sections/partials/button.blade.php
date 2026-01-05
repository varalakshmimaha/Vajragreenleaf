@php
    $text = $text ?? 'Click Here';
    $url = $url ?? '#';
    $style = $style ?? 'primary';
    $size = $size ?? 'md';

    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-300';

    $sizeClasses = match($size) {
        'sm' => 'px-4 py-2 text-sm',
        'lg' => 'px-8 py-4 text-lg',
        default => 'px-6 py-3',
    };

    $styleClasses = match($style) {
        'secondary' => 'bg-secondary text-white hover:bg-secondary/90',
        'outline' => 'border-2 border-primary text-primary hover:bg-primary hover:text-white',
        'ghost' => 'text-primary hover:bg-primary/10',
        'white' => 'bg-white text-gray-900 hover:bg-gray-100',
        default => 'bg-primary text-white hover:bg-primary/90 shadow-lg hover:shadow-xl',
    };
@endphp

<a href="{{ $url }}" class="{{ $baseClasses }} {{ $sizeClasses }} {{ $styleClasses }}">
    {{ $text }}
</a>
