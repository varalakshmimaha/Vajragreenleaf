@extends('layouts.admin')

@section('title', isset($video) ? 'Edit Video' : 'Create Video')

@section('content')
    <div class="mb-8 text-black">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($video) ? 'Edit Video' : 'Create Video' }}</h1>
    </div>

    <form action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($video))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Video Details</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video Title *</label>
                        <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" x-data="{ videoType: '{{ old('type', $video->type ?? 'youtube') }}' }">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video Type *</label>
                            <select name="type" x-model="videoType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="upload">Upload File</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="Product" {{ (old('category', $video->category ?? '') == 'Product') ? 'selected' : '' }}>Product</option>
                                <option value="Training" {{ (old('category', $video->category ?? '') == 'Training') ? 'selected' : '' }}>Training</option>
                                <option value="Testimonial" {{ (old('category', $video->category ?? '') == 'Testimonial') ? 'selected' : '' }}>Testimonial</option>
                                <option value="Event" {{ (old('category', $video->category ?? '') == 'Event') ? 'selected' : '' }}>Event</option>
                                <option value="Awareness" {{ (old('category', $video->category ?? '') == 'Awareness') ? 'selected' : '' }}>Awareness</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 mt-4" x-show="videoType !== 'upload'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video URL *</label>
                            <input type="text" name="url" value="{{ old('url', $video->url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Provide the full URL to the video.</p>
                        </div>

                        <div class="md:col-span-2 mt-4" x-show="videoType === 'upload'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video File *</label>
                            @if(isset($video) && $video->type === 'upload' && $video->url)
                                <div class="mb-2 text-sm text-blue-600">
                                    Current file: <a href="{{ asset('storage/' . $video->url) }}" target="_blank" class="underline">View Video</a>
                                </div>
                            @endif
                            <input type="file" name="video_file" accept="video/*" class="w-full">
                            <p class="text-sm text-gray-500 mt-1">Upload MP4, WebM (max 100MB).</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $video->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Video Thumbnail</h2>
                    <div class="mb-4">
                        @if(isset($video) && $video->thumbnail)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $video->thumbnail) }}" class="h-48 rounded shadow-sm">
                            </div>
                        @else
                           <p class="text-sm text-gray-500 mb-2">If not provided for YouTube/Vimeo, it will attempt to fetch automatically (if implemented) or use a placeholder.</p>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Upload JPG or PNG (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Status & Order</h2>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="order" value="{{ old('order', $video->order ?? 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-bold">
                            {{ isset($video) ? 'Update Video' : 'Save Video' }}
                        </button>
                        <a href="{{ route('admin.videos.index') }}" class="w-full text-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
