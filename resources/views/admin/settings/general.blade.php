@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">General Settings</h1>

        <!-- Settings Tabs -->
        <div class="flex space-x-4 mt-6 border-b">
            <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.general') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">General</a>
            <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.seo') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">SEO</a>
            <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.social') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Social Links</a>
            <a href="{{ route('admin.settings.contact') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.contact') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Contact</a>
            <a href="{{ route('admin.settings.scripts') }}" class="px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.scripts') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Scripts</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Title</label>
                    <input type="text" name="site_title" value="{{ \App\Models\SiteSetting::get('site_title') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ \App\Models\SiteSetting::get('site_tagline') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo (Dark)</label>
                    @php $logo = \App\Models\SiteSetting::get('logo'); @endphp
                    @if($logo)
                        <div class="mb-2 p-2 border rounded bg-gray-50 inline-block">
                            @if(file_exists(storage_path('app/public/' . $logo)))
                                <img src="{{ asset('storage/' . $logo) }}" class="h-12">
                            @else
                                <span class="text-xs text-red-500"><i class="fas fa-exclamation-triangle"></i> File missing on disk</span>
                            @endif
                        </div>
                    @endif
                    <input type="file" name="logo" class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo (Light)</label>
                    @php $logoLight = \App\Models\SiteSetting::get('logo_light'); @endphp
                    @if($logoLight)
                        <div class="mb-2 p-2 border rounded bg-gray-800 inline-block">
                            @if(file_exists(storage_path('app/public/' . $logoLight)))
                                <img src="{{ asset('storage/' . $logoLight) }}" class="h-12">
                            @else
                                <span class="text-xs text-red-500"><i class="fas fa-exclamation-triangle"></i> File missing on disk</span>
                            @endif
                        </div>
                    @endif
                    <input type="file" name="logo_light" class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    @php $favicon = \App\Models\SiteSetting::get('favicon'); @endphp
                    @if($favicon)
                        <div class="mb-2 p-2 border rounded bg-gray-50 inline-block">
                            @if(file_exists(storage_path('app/public/' . $favicon)))
                                <img src="{{ asset('storage/' . $favicon) }}" class="h-8">
                            @else
                                <span class="text-xs text-red-500"><i class="fas fa-exclamation-triangle"></i> File missing on disk</span>
                            @endif
                        </div>
                    @endif
                    <input type="file" name="favicon" class="w-full">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Footer Text</label>
                <textarea name="footer_text" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ \App\Models\SiteSetting::get('footer_text') }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Settings
            </button>
        </form>
    </div>
@endsection
