<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $seoSettings['site_title'] ?? 'IT Business')</title>
    <meta name="description" content="@yield('meta_description', $seoSettings['site_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $seoSettings['site_keywords'] ?? '')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', $seoSettings['site_title'] ?? 'IT Business')">
    <meta property="og:description" content="@yield('meta_description', $seoSettings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', $seoSettings['og_image'] ? asset('storage/' . $seoSettings['og_image']) : '')">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    @if(!empty($siteSettings['favicon']))
        @php
            $faviconUrl = asset('storage/' . $siteSettings['favicon']);
            $ext = pathinfo($siteSettings['favicon'], PATHINFO_EXTENSION);
            $type = $ext == 'ico' ? 'image/x-icon' : 'image/' . $ext;
        @endphp
        <link rel="icon" type="{{ $type }}" href="{{ $faviconUrl }}">
    @endif

    <!-- Google Fonts -->
    @php
        $headingFont = $activeTheme->heading_font ?? 'Poppins';
        $bodyFont = $activeTheme->body_font ?? 'Inter';
        $fontsToLoad = collect([$headingFont, $bodyFont])->unique()->implode('&family=');
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontsToLoad) }}:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Theme CSS Variables -->
    @if($activeTheme)
        <style>{!! $activeTheme->getCssVariables() !!}</style>
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--color-primary, #2563eb)',
                        secondary: 'var(--color-secondary, #1e40af)',
                        accent: 'var(--color-accent, #f59e0b)',
                    },
                    fontFamily: {
                        heading: ['{{ $headingFont }}', 'sans-serif'],
                        body: ['{{ $bodyFont }}', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Custom Animations & Styles -->
    <style>
        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Apply theme fonts */
        body { font-family: var(--font-body), 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-heading), 'Poppins', sans-serif; }

        /* Keyframe animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(var(--color-primary-rgb, 37, 99, 235), 0.3); }
            50% { box-shadow: 0 0 40px rgba(var(--color-primary-rgb, 37, 99, 235), 0.6); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(100px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3); }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Animation classes */
        .animate-fade-in { animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-fade-in-down { animation: fadeInDown 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-fade-in-left { animation: fadeInLeft 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-fade-in-right { animation: fadeInRight 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-scale-in { animation: scaleIn 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }
        .animate-bounce-in { animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }

        /* Scroll animations */
        [data-animate] { opacity: 0; transform: translateY(30px); }
        [data-animate].animated { opacity: 1; transform: translateY(0); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }

        /* Gradient utilities */
        .gradient-primary { background: linear-gradient(135deg, var(--color-primary, #2563eb) 0%, var(--color-secondary, #1e40af) 100%); }
        .gradient-accent { background: linear-gradient(135deg, var(--color-primary, #2563eb) 0%, var(--color-accent, #f59e0b) 100%); }
        .gradient-text {
            background: linear-gradient(135deg, var(--color-primary, #2563eb), var(--color-accent, #f59e0b));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-border {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box, linear-gradient(135deg, var(--color-primary), var(--color-accent)) border-box;
        }

        /* Hover effects */
        .hover-lift { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); }

        .hover-scale { transition: transform 0.3s ease; }
        .hover-scale:hover { transform: scale(1.05); }

        .hover-glow { transition: all 0.3s ease; }
        .hover-glow:hover { box-shadow: 0 0 30px rgba(var(--color-primary-rgb, 37, 99, 235), 0.4); }

        /* Card styles */
        .card-modern {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .card-modern:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(-4px);
        }

        /* Glass effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Text shadow for headers */
        .text-shadow { text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .text-shadow-lg { text-shadow: 0 4px 20px rgba(0,0,0,0.15); }

        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary, #2563eb), var(--color-secondary, #1e40af));
            color: white !important;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            display: inline-block;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.5);
        }

        /* Theme color classes - Override Tailwind */
        .text-primary { color: var(--color-primary, #2563eb) !important; }
        .bg-primary { background-color: var(--color-primary, #2563eb) !important; }
        .border-primary { border-color: var(--color-primary, #2563eb) !important; }
        .text-secondary { color: var(--color-secondary, #1e40af) !important; }
        .bg-secondary { background-color: var(--color-secondary, #1e40af) !important; }
        .text-accent { color: var(--color-accent, #f59e0b) !important; }
        .bg-accent { background-color: var(--color-accent, #f59e0b) !important; }

        /* Hover states */
        .hover\:text-primary:hover { color: var(--color-primary, #2563eb) !important; }
        .hover\:bg-primary:hover { background-color: var(--color-primary, #2563eb) !important; }
        .group:hover .group-hover\:text-primary { color: var(--color-primary, #2563eb) !important; }

        /* Background opacity variants */
        .bg-primary\/10 { background-color: rgba(37, 99, 235, 0.1) !important; }
        .bg-primary\/20 { background-color: rgba(37, 99, 235, 0.2) !important; }
        .hover\:bg-primary\/10:hover { background-color: rgba(37, 99, 235, 0.1) !important; }
        .hover\:bg-primary\/20:hover { background-color: rgba(37, 99, 235, 0.2) !important; }

        /* Focus styles */
        .focus\:ring-primary\/50:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.5) !important; }
        .focus\:border-primary:focus { border-color: var(--color-primary, #2563eb) !important; }

        /* Section padding */
        .section-padding { padding: 5rem 0; }
        @media (min-width: 768px) { .section-padding { padding: 7rem 0; } }

        /* Decorative elements */
        .blob {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation: blob-morph 8s ease-in-out infinite;
        }
        @keyframes blob-morph {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }

        /* Line clamp */
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    <!-- Head Scripts -->
    {!! $scripts['head_scripts'] ?? '' !!}

    @stack('styles')
</head>
<body class="font-body antialiased bg-gray-50 text-gray-800">
    {!! $scripts['body_start_scripts'] ?? '' !!}

    <!-- Header -->
    @include('components.frontend.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.frontend.footer')

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('[data-animate]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const animation = el.dataset.animate;
                        const delay = el.dataset.delay || 0;

                        setTimeout(() => {
                            el.classList.add('animated', animation);
                        }, delay);

                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.1 });

            animatedElements.forEach(el => observer.observe(el));
        });
    </script>

    <!-- Body End Scripts -->
    {!! $scripts['body_end_scripts'] ?? '' !!}

    @stack('scripts')
</body>
</html>
