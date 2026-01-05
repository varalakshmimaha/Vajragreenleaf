@extends('layouts.admin')

@section('title', 'Theme Management')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Theme Management</h1>
        <a href="{{ route('admin.themes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Theme
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($themes as $theme)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Theme Preview -->
                <div class="h-32 p-4" style="background: linear-gradient(135deg, {{ $theme->primary_color }} 0%, {{ $theme->secondary_color }} 100%);">
                    <div class="flex space-x-2">
                        <div class="w-8 h-8 rounded-full" style="background-color: {{ $theme->primary_color }};"></div>
                        <div class="w-8 h-8 rounded-full" style="background-color: {{ $theme->secondary_color }};"></div>
                        <div class="w-8 h-8 rounded-full" style="background-color: {{ $theme->accent_color }};"></div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $theme->name }}</h3>
                            @if($theme->is_active)
                                <span class="text-xs text-green-600 font-medium">Active Theme</span>
                            @endif
                        </div>
                        @if($theme->is_active)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        @if(!$theme->is_active)
                            <form action="{{ route('admin.themes.activate', $theme) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-100 text-green-700 px-3 py-2 rounded-lg hover:bg-green-200 text-sm">
                                    <i class="fas fa-check mr-1"></i> Activate
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.themes.edit', $theme) }}" class="flex-1 text-center bg-blue-100 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-200 text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        @if(!$theme->is_active)
                            <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" onsubmit="return confirm('Delete this theme?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl shadow-sm">
                <i class="fas fa-palette text-4xl mb-4"></i>
                <p>No themes created yet.</p>
            </div>
        @endforelse
    </div>
@endsection
