@extends('layouts.admin')

@section('title', 'Sections')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sections</h1>
            <p class="text-gray-600">Create and manage reusable page sections</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Section
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Layout Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.sections.index') }}"
               class="px-4 py-2 rounded-lg {{ !request('layout') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            @foreach(\App\Models\Section::getLayoutOptions() as $key => $label)
                <a href="{{ route('admin.sections.index', ['layout' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm {{ request('layout') === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if($sections->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sections as $section)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Preview thumbnail -->
                    <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                        @if($section->image)
                            <img src="{{ asset('storage/' . $section->image) }}" alt="{{ $section->name }}"
                                 class="w-full h-full object-cover">
                        @elseif($section->background_image)
                            <img src="{{ asset('storage/' . $section->background_image) }}" alt="{{ $section->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-layer-group text-4xl text-gray-400"></i>
                            </div>
                        @endif

                        <!-- Layout badge -->
                        <span class="absolute top-3 left-3 px-2 py-1 bg-black bg-opacity-60 text-white text-xs rounded">
                            {{ \App\Models\Section::getLayoutOptions()[$section->layout] ?? $section->layout }}
                        </span>

                        <!-- Status badge -->
                        <span class="absolute top-3 right-3 px-2 py-1 rounded text-xs {{ $section->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $section->name }}</h3>
                        @if($section->title)
                            <p class="text-sm text-gray-600 truncate">{{ $section->title }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $section->pages_count }} {{ Str::plural('page', $section->pages_count) }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.sections.preview', $section) }}"
                                   class="text-gray-600 hover:text-gray-800 p-2" title="Preview" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.sections.duplicate', $section) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-800 p-2" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.sections.edit', $section) }}"
                                   class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sections.destroy', $section) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this section?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $sections->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Sections Yet</h3>
            <p class="text-gray-500 mb-6">Create your first reusable section to get started.</p>
            <a href="{{ route('admin.sections.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Create First Section
            </a>
        </div>
    @endif
@endsection
