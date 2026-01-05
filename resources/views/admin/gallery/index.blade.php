@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gallery</h1>
            <p class="text-gray-600 mt-1">Manage your image gallery</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.gallery.categories') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center">
                <i class="fas fa-folder mr-2"></i> Categories
            </a>
            <a href="{{ route('admin.gallery.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Image
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 p-6">
            @forelse($galleries as $gallery)
                <div class="group relative bg-gray-100 rounded-lg overflow-hidden aspect-square">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                        class="w-full h-full object-cover">

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3">
                        <div class="flex justify-between items-start">
                            @if($gallery->is_featured)
                                <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                            @else
                                <span></span>
                            @endif
                            <span class="text-white text-xs {{ $gallery->is_active ? 'bg-green-500' : 'bg-red-500' }} px-2 py-1 rounded">
                                {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-white font-medium text-sm truncate">{{ $gallery->title }}</h3>
                            @if($gallery->category)
                                <p class="text-gray-300 text-xs">{{ $gallery->category->name }}</p>
                            @endif
                        </div>

                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.gallery.edit', $gallery) }}"
                               class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white p-2 rounded hover:bg-red-700 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fas fa-images text-6xl mb-4"></i>
                    <p class="text-xl">No images in gallery</p>
                    <a href="{{ route('admin.gallery.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                        Add your first image
                    </a>
                </div>
            @endforelse
        </div>

        @if($galleries->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
@endsection
