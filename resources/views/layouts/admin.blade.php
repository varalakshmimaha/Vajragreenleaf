<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Vajra green leaf</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="bg-gray-900 text-white w-64 flex-shrink-0 transition-all duration-300" :class="{ '-ml-64': !sidebarOpen }">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="p-4 border-b border-gray-800">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">Vajra green leaf</a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Content</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.pages.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Pages
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.banners.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.banners.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Banners
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.menus.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.menus.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                Menus
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.sections.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.sections.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                Sections
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Modules</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.services.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                Services
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Products
                            </a>
                        </li>



                        <li>
                            <a href="{{ route('admin.blogs.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.blogs.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Blog
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.team.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.team.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Team
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.careers.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.careers.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Careers
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.gallery.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.gallery.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Gallery
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Extra</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.testimonials.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Testimonials
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.certifications.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.certifications.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-certificate w-5 h-5 mr-3 flex items-center justify-center"></i>
                                Certifications
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.awards.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.awards.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-trophy w-5 h-5 mr-3 flex items-center justify-center"></i>
                                Awards & Rewards
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.videos.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.videos.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-video w-5 h-5 mr-3 flex items-center justify-center"></i>
                                Videos
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.bankers.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.bankers.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-university w-5 h-5 mr-3 flex items-center justify-center"></i>
                                Our Bankers
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.clients.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.clients.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Clients
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.counters.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.counters.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Counters
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.how-we-work.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.how-we-work.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                How We Work
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Enquiries</span>
                        </li>



                        <li>
                            <a href="{{ route('admin.enquiries.index', ['type' => 'contact']) }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->get('type') == 'contact' ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Contact Messages
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Settings</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.settings.general') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Site Settings
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.themes.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.themes.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                Themes
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">User Management</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Users
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Roles
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                Permissions
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-4 text-xs font-semibold text-gray-500 uppercase">Reports & Analytics</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Reports & References
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Profile
                                </a>
                                <hr class="my-1">
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
