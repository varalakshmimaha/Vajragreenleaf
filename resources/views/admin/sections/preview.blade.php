<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Preview - {{ $section->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @if($section->custom_css)
            {!! $section->custom_css !!}
        @endif

        /* Animation styles */
        .animate-on-scroll {
            opacity: 0;
            transition: all 0.5s ease-out;
        }
        .animate-on-scroll.visible {
            opacity: 1;
        }
        .animation-fade.visible { opacity: 1; }
        .animation-slide-up { transform: translateY(50px); }
        .animation-slide-up.visible { transform: translateY(0); }
        .animation-slide-down { transform: translateY(-50px); }
        .animation-slide-down.visible { transform: translateY(0); }
        .animation-slide-left { transform: translateX(50px); }
        .animation-slide-left.visible { transform: translateX(0); }
        .animation-slide-right { transform: translateX(-50px); }
        .animation-slide-right.visible { transform: translateX(0); }
        .animation-zoom { transform: scale(0.8); }
        .animation-zoom.visible { transform: scale(1); }
        .animation-bounce.visible { animation: bounce 1s; }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        /* Card hover effects */
        .card-hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .card-hover-scale:hover { transform: scale(1.02); }
        .card-hover-glow:hover { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        .card-hover-border:hover { border-color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Preview Header -->
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">Preview:</span>
                <span class="font-semibold">{{ $section->name }}</span>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ \App\Models\Section::getLayoutOptions()[$section->layout] ?? $section->layout }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sections.edit', $section) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <button onclick="window.close()" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times mr-1"></i> Close
                </button>
            </div>
        </div>
    </div>

    <!-- Section Preview -->
    <div class="mt-4">
        @include('components.section-renderer', ['section' => $section])
    </div>

    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-on-scroll');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, parseInt(entry.target.dataset.delay || 0));
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => observer.observe(el));
        });

        @if($section->custom_js)
            {!! $section->custom_js !!}
        @endif
    </script>
</body>
</html>
