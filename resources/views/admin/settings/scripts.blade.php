@extends('layouts.admin')

@section('title', 'Scripts Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Scripts Settings</h1>

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
        <form action="{{ route('admin.settings.scripts.update') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                <input type="text" name="google_analytics_id" value="{{ \App\Models\SiteSetting::get('google_analytics_id') }}" placeholder="G-XXXXXXXXXX"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Your GA4 measurement ID.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Pixel ID</label>
                <input type="text" name="meta_pixel_id" value="{{ \App\Models\SiteSetting::get('meta_pixel_id') }}" placeholder="XXXXXXXXXXXXXXXX"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Facebook/Meta Pixel ID for tracking.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Tag Manager ID</label>
                <input type="text" name="gtm_id" value="{{ \App\Models\SiteSetting::get('gtm_id') }}" placeholder="GTM-XXXXXXX"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Scripts</label>
                <textarea name="header_scripts" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="<!-- Add your custom scripts here -->">{{ \App\Models\SiteSetting::get('header_scripts') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Scripts placed in the &lt;head&gt; section.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Footer Scripts</label>
                <textarea name="footer_scripts" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="<!-- Add your custom scripts here -->">{{ \App\Models\SiteSetting::get('footer_scripts') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Scripts placed before the closing &lt;/body&gt; tag.</p>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Settings
            </button>
        </form>
    </div>
@endsection
