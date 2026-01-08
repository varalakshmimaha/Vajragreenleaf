@extends('layouts.admin')

@section('title', 'Social Links')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Social Links</h1>

        <!-- Settings Tabs -->
        <div class="flex space-x-4 mt-6 border-b">
            <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.general') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">General</a>
            <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.seo') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">SEO</a>
            <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.social') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Social Links</a>
            <a href="{{ route('admin.settings.contact') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.contact') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Contact</a>
            <a href="{{ route('admin.settings.scripts') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.scripts') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Scripts</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.settings.social.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i> Facebook
                    </label>
                    <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-twitter text-blue-400 mr-2"></i> Twitter / X
                    </label>
                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/yourhandle"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-linkedin text-blue-700 mr-2"></i> LinkedIn
                    </label>
                    <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-2"></i> Instagram
                    </label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/yourhandle"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-youtube text-red-600 mr-2"></i> YouTube
                    </label>
                    <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/yourchannel"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-github text-gray-800 mr-2"></i> GitHub
                    </label>
                    <input type="url" name="social_github" value="{{ $settings['social_github'] ?? '' }}" placeholder="https://github.com/yourorg"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Settings
            </button>
        </form>
    </div>
@endsection