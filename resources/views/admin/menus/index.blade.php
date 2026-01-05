@extends('layouts.admin')

@section('title', 'Menu Management')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Menu Management</h1>
        <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Menu
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($menus as $menu)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $menu->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $menu->location }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">{{ $menu->items->count() }} menu items</p>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="flex-1 text-center bg-blue-100 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-200 text-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.menus.items', $menu) }}" class="flex-1 text-center bg-purple-100 text-purple-700 px-3 py-2 rounded-lg hover:bg-purple-200 text-sm">
                        <i class="fas fa-list mr-1"></i> Items
                    </a>
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Delete this menu?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl shadow-sm">
                <i class="fas fa-bars text-4xl mb-4"></i>
                <p>No menus created yet.</p>
            </div>
        @endforelse
    </div>
@endsection
