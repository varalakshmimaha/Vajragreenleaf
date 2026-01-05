@extends('layouts.admin')

@section('title', isset($menu) ? 'Edit Menu' : 'Create Menu')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($menu) ? 'Edit Menu' : 'Create Menu' }}</h1>
    </div>

    <form action="{{ isset($menu) ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" method="POST">
        @csrf
        @if(isset($menu))
            @method('PUT')
        @endif

        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Name *</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <select name="location" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Location</option>
                        <option value="header" {{ old('location', $menu->location ?? '') == 'header' ? 'selected' : '' }}>Header</option>
                        <option value="footer_col1" {{ old('location', $menu->location ?? '') == 'footer_col1' ? 'selected' : '' }}>Footer Column 1</option>
                        <option value="footer_col2" {{ old('location', $menu->location ?? '') == 'footer_col2' ? 'selected' : '' }}>Footer Column 2</option>
                        <option value="footer_col3" {{ old('location', $menu->location ?? '') == 'footer_col3' ? 'selected' : '' }}>Footer Column 3</option>
                    </select>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        {{ isset($menu) ? 'Update Menu' : 'Create Menu' }}
                    </button>
                    <a href="{{ route('admin.menus.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
