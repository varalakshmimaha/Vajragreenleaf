@extends('layouts.admin')

@section('title', 'Contact Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Contact Settings</h1>

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
        <form action="{{ route('admin.settings.contact.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" placeholder="+1234567890"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Include country code.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="contact_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['contact_address'] ?? '' }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed URL</label>
                <input type="url" name="google_maps_url" value="{{ $settings['google_maps_url'] ?? '' }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Get this from Google Maps embed code (src URL).</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Business Hours</label>
                <textarea name="business_hours" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 4:00 PM&#10;Sunday: Closed">{{ $settings['business_hours'] ?? '' }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Save Settings
            </button>
        </form>
    </div>
@endsection
