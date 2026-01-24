<header class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300" id="main-header">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                @if(!empty($siteSettings['logo']))
                    <img src="{{ asset('storage/' . $siteSettings['logo']) }}" alt="{{ $siteSettings['site_title'] ?? 'Logo' }}" class="h-10 md:h-12">
                @else
                    <span class="text-2xl font-bold gradient-text">{{ $siteSettings['site_title'] ?? 'IT Business' }}</span>
                @endif
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                @if($headerMenu)
                    @foreach($headerMenu->items as $item)
                        @if($item->children->count() > 0)
                            <div class="relative group">
                                <button class="flex items-center space-x-1 text-gray-700 hover:text-primary transition-colors font-medium">
                                    <span>{{ $item->title }}</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top scale-95 group-hover:scale-100">
                                    <div class="py-2">
                                        @foreach($item->children as $child)
                                            <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-4 py-2 text-gray-700 hover:bg-primary/10 hover:text-primary transition-colors">
                                                {{ $child->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-gray-700 hover:text-primary transition-colors font-medium">
                                {{ $item->title }}
                            </a>
                        @endif
                    @endforeach
                @endif

                <div class="flex items-center ml-4">
                    @auth
                        <div class="relative group" x-data="{ open: false }">
                            <button @click="open = !open" class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm border border-primary/20">
                                <i class="fas fa-user"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-50 flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->username }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all shadow-sm border border-gray-100 group" title="Login / Register">
                            <i class="fas fa-user-circle text-xl group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-gray-700 focus:outline-none" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="lg:hidden hidden mt-4 pb-4" id="mobile-menu">
            <div class="flex flex-col space-y-3">
                @if($headerMenu)
                    @foreach($headerMenu->items as $item)
                        <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-gray-700 hover:text-primary transition-colors font-medium py-2">
                            {{ $item->title }}
                        </a>
                        @if($item->children->count() > 0)
                            @foreach($item->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="text-gray-600 hover:text-primary transition-colors pl-4 py-1">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        @endif
                    @endforeach
                @endif
                <a href="{{ route('careers.index') }}" class="text-gray-700 hover:text-primary transition-colors font-medium py-2 {{ request()->routeIs('careers.*') ? 'text-primary' : '' }}">
                    Careers
                </a>
                <a href="{{ route('gallery.index') }}" class="text-gray-700 hover:text-primary transition-colors font-medium py-2 {{ request()->routeIs('gallery.*') ? 'text-primary' : '' }}">
                    Gallery
                </a>
                
                <div class="flex flex-col space-y-3 mt-2 pt-3 border-t border-gray-100">
                    @auth
                        <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
                            </div>
                        </div>
                        <a href="{{ route('user.dashboard') }}" class="text-center font-bold py-2 rounded-xl border border-gray-200 text-gray-700">Go to Dashboard</a>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center font-bold py-2 rounded-xl text-red-600 bg-red-50 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}" class="text-center text-white gradient-primary font-bold py-3 rounded-xl shadow-lg transition-all active:scale-95">
                            Login / Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    window.addEventListener('scroll', function() {
        const header = document.getElementById('main-header');
        if (window.scrollY > 50) {
            header.classList.add('shadow-md', 'py-2');
        } else {
            header.classList.remove('shadow-md', 'py-2');
        }
    });
</script>
