@extends('layouts.admin')

@section('title', isset($banker) ? 'Edit Banker' : 'Add New Banker')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($banker) ? 'Edit Banker' : 'Add New Banker' }}</h1>
    </div>

    <form action="{{ isset($banker) ? route('admin.bankers.update', $banker) : route('admin.bankers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($banker))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Banker Details</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                        <input type="text" name="name" value="{{ old('name', $banker->name ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="description" rows="3" maxlength="500"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $banker->description ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 characters.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Official Website URL</label>
                        <input type="url" name="website_url" value="{{ old('website_url', $banker->website_url ?? '') }}" placeholder="https://www.bank.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Bank Logo / Image</h2>
                    <div class="mb-4">
                        @if(isset($banker) && $banker->logo)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $banker->logo) }}" class="h-32 w-32 object-contain border rounded p-2 bg-gray-50">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" {{ !isset($banker) ? 'required' : '' }} class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Upload JPG, PNG, or WEBP (max 2MB). Recommended aspect ratio: Square.</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Status & Order</h2>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banker->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active (Visible on website)</span>
                        </label>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="order" value="{{ old('order', $banker->order ?? 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-bold shadow-md">
                            {{ isset($banker) ? 'Update Banker' : 'Save Banker' }}
                        </button>
                        <a href="{{ route('admin.bankers.index') }}" class="w-full text-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
