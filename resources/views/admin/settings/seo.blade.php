@extends('layouts.admin')

@section('title', 'SEO Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">SEO Settings</h1>

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
        <form action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Title</label>
                <input type="text" name="meta_title" value="{{ \App\Models\SiteSetting::get('meta_title') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Used when pages don't have their own meta title.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Description</label>
                <textarea name="meta_description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ \App\Models\SiteSetting::get('meta_description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ \App\Models\SiteSetting::get('meta_keywords') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Comma-separated keywords.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">OG Image</label>
                @if(\App\Models\SiteSetting::get('og_image'))
                    <img src="{{ asset('storage/' . \App\Models\SiteSetting::get('og_image')) }}" class="h-24 mb-2 rounded">
                @endif
                <input type="file" name="og_image" class="w-full">
                <p class="text-sm text-gray-500 mt-1">Recommended: 1200x630 pixels.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Robots Meta Tag</label>
                <select name="robots" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="index, follow" {{ \App\Models\SiteSetting::get('robots') == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                    <option value="noindex, follow" {{ \App\Models\SiteSetting::get('robots') == 'noindex, follow' ? 'selected' : '' }}>No Index, Follow</option>
                    <option value="index, nofollow" {{ \App\Models\SiteSetting::get('robots') == 'index, nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                    <option value="noindex, nofollow" {{ \App\Models\SiteSetting::get('robots') == 'noindex, nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Settings
            </button>
        </form>
    </div>
@endsection
