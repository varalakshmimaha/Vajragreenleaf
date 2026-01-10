@extends('layouts.admin')

@section('title', isset($section) ? 'Edit Section' : 'Create Section')

@push('styles')
<style>
    .tab-btn.active { border-color: #3b82f6; color: #3b82f6; background-color: #eff6ff; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .color-preview { width: 40px; height: 40px; border-radius: 8px; border: 2px solid #e5e7eb; }
    .item-card { transition: all 0.2s; }
    .item-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($section) ? 'Edit Section' : 'Create Section' }}</h1>
            <p class="text-gray-600">Design and configure your section</p>
        </div>
        <a href="{{ route('admin.sections.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($section) ? route('admin.sections.update', $section) : route('admin.sections.store') }}"
          method="POST" enctype="multipart/form-data" x-data="sectionForm()">
        @csrf
        @if(isset($section))
            @method('PUT')
        @endif

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="flex border-b overflow-x-auto">
                <button type="button" class="tab-btn active px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="basic">
                    <i class="fas fa-info-circle mr-2"></i> Basic Info
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="content">
                    <i class="fas fa-align-left mr-2"></i> Content
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="media">
                    <i class="fas fa-image mr-2"></i> Media
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="design">
                    <i class="fas fa-palette mr-2"></i> Design
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="layout">
                    <i class="fas fa-th-large mr-2"></i> Layout
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="animation">
                    <i class="fas fa-magic mr-2"></i> Animation
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="items">
                    <i class="fas fa-list mr-2"></i> Items/Cards
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="buttons">
                    <i class="fas fa-mouse-pointer mr-2"></i> Buttons
                </button>
                <button type="button" class="tab-btn px-6 py-4 border-b-2 border-transparent font-medium whitespace-nowrap" data-tab="advanced">
                    <i class="fas fa-code mr-2"></i> Advanced
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Basic Info Tab -->
                <div class="tab-content active bg-white rounded-xl shadow-sm p-6" id="tab-basic">
                    <h2 class="text-lg font-semibold mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Section Name * <span class="text-gray-400">(Internal)</span></label>
                            <input type="text" name="name" value="{{ old('name', $section->name ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Layout Type *</label>
                            <select name="layout" x-model="layout" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach($layouts as $key => $label)
                                    <option value="{{ $key }}" {{ old('layout', $section->layout ?? 'default') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-gray-400">(Visible on page)</span></label>
                        <input type="text" name="title" value="{{ old('title', $section->title ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $section->description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Content Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-content">
                    <h2 class="text-lg font-semibold mb-4">Rich Content</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content (HTML Supported)</label>
                        <textarea name="content" id="contentEditor" rows="15"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('content', $section->content ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
                    </div>
                </div>

                <!-- Media Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-media" x-data="{ imageSource: 'file' }">
                    <h2 class="text-lg font-semibold mb-4">Media</h2>

                    <!-- Main Image Section -->
                    <div class="p-4 bg-gray-50 rounded-lg mb-6">
                        <h3 class="font-medium mb-3">Main Image</h3>

                        @if(isset($section) && $section->image)
                            <div class="mb-4 relative inline-block">
                                <img src="{{ asset('storage/' . $section->image) }}" class="h-32 rounded-lg" id="currentImagePreview">
                                <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">Current</span>
                            </div>
                        @endif

                        <!-- Image Source Toggle -->
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="image_source" value="file" x-model="imageSource" class="mr-2">
                                <span class="text-sm text-gray-700">Upload File</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="image_source" value="url" x-model="imageSource" class="mr-2">
                                <span class="text-sm text-gray-700">Image URL</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- File Upload -->
                            <div x-show="imageSource === 'file'" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                <input type="file" name="image" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Max 5MB. Supports: JPG, PNG, GIF, WEBP, SVG</p>
                            </div>

                            <!-- URL Input -->
                            <div x-show="imageSource === 'url'" x-transition class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                                <div class="flex gap-2">
                                    <input type="url" name="image_url" id="imageUrlInput"
                                           placeholder="https://example.com/image.jpg"
                                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <button type="button" onclick="previewImageUrl('imageUrlInput', 'urlImagePreview', 'urlPreviewImg')"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                        <i class="fas fa-eye mr-1"></i> Preview
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Enter a valid image URL. The image will be downloaded and stored locally.</p>
                                <div id="urlImagePreview" class="mt-3 hidden">
                                    <img id="urlPreviewImg" src="" class="h-32 rounded-lg border" onerror="this.parentElement.classList.add('hidden'); alert('Failed to load image. Please check the URL.');">
                                </div>
                            </div>

                            <!-- Image Position -->
                            <div x-show="imageSource === 'file'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image Position</label>
                                <select name="image_position" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="left" {{ old('image_position', $section->image_position ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="right" {{ old('image_position', $section->image_position ?? '') === 'right' ? 'selected' : '' }}>Right</option>
                                    <option value="top" {{ old('image_position', $section->image_position ?? '') === 'top' ? 'selected' : '' }}>Top</option>
                                    <option value="bottom" {{ old('image_position', $section->image_position ?? '') === 'bottom' ? 'selected' : '' }}>Bottom</option>
                                    <option value="background" {{ old('image_position', $section->image_position ?? '') === 'background' ? 'selected' : '' }}>Background</option>
                                    <option value="none" {{ old('image_position', $section->image_position ?? '') === 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>

                        <!-- Image Position (shown when URL selected) -->
                        <div x-show="imageSource === 'url'" class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image Position</label>
                            <select name="image_position" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="left" {{ old('image_position', $section->image_position ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                <option value="right" {{ old('image_position', $section->image_position ?? '') === 'right' ? 'selected' : '' }}>Right</option>
                                <option value="top" {{ old('image_position', $section->image_position ?? '') === 'top' ? 'selected' : '' }}>Top</option>
                                <option value="bottom" {{ old('image_position', $section->image_position ?? '') === 'bottom' ? 'selected' : '' }}>Bottom</option>
                                <option value="background" {{ old('image_position', $section->image_position ?? '') === 'background' ? 'selected' : '' }}>Background</option>
                                <option value="none" {{ old('image_position', $section->image_position ?? '') === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gallery -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                        @if(isset($section) && $section->gallery)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($section->gallery as $index => $image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image) }}" class="h-20 w-20 object-cover rounded-lg">
                                        <label class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" name="delete_gallery_images[]" value="{{ $index }}" class="hidden">
                                            <i class="fas fa-times text-xs"></i>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="gallery_images[]" multiple accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Video -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video URL (YouTube/Vimeo)</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $section->video_url ?? '') }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Design Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-design" x-data="{ bgImageSource: 'file' }">
                    <h2 class="text-lg font-semibold mb-4">Design & Styling</h2>

                    <!-- Background -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium mb-3">Background</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Type</label>
                                <select name="background_type" x-model="bgType"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="color">Solid Color</option>
                                    <option value="gradient">Gradient</option>
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                </select>
                            </div>

                            <div x-show="bgType === 'color'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="background_color" value="{{ old('background_color', $section->background_color ?? '#ffffff') }}"
                                           class="color-preview cursor-pointer">
                                    <input type="text" value="{{ old('background_color', $section->background_color ?? '#ffffff') }}"
                                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg" readonly>
                                </div>
                            </div>

                            <div x-show="bgType === 'gradient'" class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gradient CSS</label>
                                <input type="text" name="background_gradient"
                                       value="{{ old('background_gradient', $section->background_gradient ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)') }}"
                                       placeholder="linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Background Image Options -->
                            <div x-show="bgType === 'image'" class="md:col-span-2">
                                @if(isset($section) && $section->background_image)
                                    <div class="mb-4 relative inline-block">
                                        <img src="{{ asset('storage/' . $section->background_image) }}" class="h-24 rounded-lg">
                                        <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">Current</span>
                                    </div>
                                @endif

                                <!-- Background Image Source Toggle -->
                                <div class="flex gap-4 mb-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="bg_image_source" value="file" x-model="bgImageSource" class="mr-2">
                                        <span class="text-sm text-gray-700">Upload File</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="bg_image_source" value="url" x-model="bgImageSource" class="mr-2">
                                        <span class="text-sm text-gray-700">Image URL</span>
                                    </label>
                                </div>

                                <!-- File Upload -->
                                <div x-show="bgImageSource === 'file'" x-transition>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                                    <input type="file" name="background_image" accept="image/*"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- URL Input -->
                                <div x-show="bgImageSource === 'url'" x-transition>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Background Image URL</label>
                                    <div class="flex gap-2">
                                        <input type="url" name="background_image_url" id="bgImageUrlInput"
                                               placeholder="https://example.com/background.jpg"
                                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <button type="button" onclick="previewImageUrl('bgImageUrlInput', 'bgUrlImagePreview', 'bgUrlPreviewImg')"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                            <i class="fas fa-eye mr-1"></i> Preview
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">The image will be downloaded and stored locally.</p>
                                    <div id="bgUrlImagePreview" class="mt-3 hidden">
                                        <img id="bgUrlPreviewImg" src="" class="h-24 rounded-lg border" onerror="this.parentElement.classList.add('hidden'); alert('Failed to load image.');">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Overlay</label>
                            <input type="text" name="background_overlay" value="{{ old('background_overlay', $section->background_overlay ?? '') }}"
                                   placeholder="rgba(0,0,0,0.5)"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Text Styling -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium mb-3">Text Styling</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="text_color" value="{{ old('text_color', $section->text_color ?? '#1f2937') }}"
                                           class="color-preview cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title Size</label>
                                <select name="title_size" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="text-2xl" {{ old('title_size', $section->title_size ?? '') === 'text-2xl' ? 'selected' : '' }}>Small (2xl)</option>
                                    <option value="text-3xl" {{ old('title_size', $section->title_size ?? '') === 'text-3xl' ? 'selected' : '' }}>Medium (3xl)</option>
                                    <option value="text-4xl" {{ old('title_size', $section->title_size ?? 'text-4xl') === 'text-4xl' ? 'selected' : '' }}>Large (4xl)</option>
                                    <option value="text-5xl" {{ old('title_size', $section->title_size ?? '') === 'text-5xl' ? 'selected' : '' }}>Extra Large (5xl)</option>
                                    <option value="text-6xl" {{ old('title_size', $section->title_size ?? '') === 'text-6xl' ? 'selected' : '' }}>Huge (6xl)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title Alignment</label>
                                <select name="title_alignment" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="left" {{ old('title_alignment', $section->title_alignment ?? '') === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ old('title_alignment', $section->title_alignment ?? 'center') === 'center' ? 'selected' : '' }}>Center</option>
                                    <option value="right" {{ old('title_alignment', $section->title_alignment ?? '') === 'right' ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content Alignment</label>
                            <select name="content_alignment" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="left" {{ old('content_alignment', $section->content_alignment ?? '') === 'left' ? 'selected' : '' }}>Left</option>
                                <option value="center" {{ old('content_alignment', $section->content_alignment ?? 'center') === 'center' ? 'selected' : '' }}>Center</option>
                                <option value="right" {{ old('content_alignment', $section->content_alignment ?? '') === 'right' ? 'selected' : '' }}>Right</option>
                            </select>
                        </div>
                    </div>

                    <!-- Theme Integration -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="use_theme_colors" value="1"
                                   {{ old('use_theme_colors', $section->use_theme_colors ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Use Theme Colors (primary, secondary, accent)</span>
                        </label>
                    </div>
                </div>

                <!-- Layout Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-layout">
                    <h2 class="text-lg font-semibold mb-4">Layout & Spacing</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Container Width</label>
                            <select name="container_width" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="container" {{ old('container_width', $section->container_width ?? 'container') === 'container' ? 'selected' : '' }}>Standard Container</option>
                                <option value="narrow" {{ old('container_width', $section->container_width ?? '') === 'narrow' ? 'selected' : '' }}>Narrow</option>
                                <option value="full" {{ old('container_width', $section->container_width ?? '') === 'full' ? 'selected' : '' }}>Full Width</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Grid Columns (for grid layouts)</label>
                            <select name="columns" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="1" {{ old('columns', $section->columns ?? '1') === '1' ? 'selected' : '' }}>1 Column</option>
                                <option value="2" {{ old('columns', $section->columns ?? '') === '2' ? 'selected' : '' }}>2 Columns</option>
                                <option value="3" {{ old('columns', $section->columns ?? '') === '3' ? 'selected' : '' }}>3 Columns</option>
                                <option value="4" {{ old('columns', $section->columns ?? '') === '4' ? 'selected' : '' }}>4 Columns</option>
                                <option value="6" {{ old('columns', $section->columns ?? '') === '6' ? 'selected' : '' }}>6 Columns</option>
                            </select>
                        </div>
                    </div>

                    <!-- Spacing -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium mb-3">Spacing</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Padding Top</label>
                                <select name="padding_top" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="py-8" {{ old('padding_top', $section->padding_top ?? '') === 'py-8' ? 'selected' : '' }}>Small</option>
                                    <option value="py-12" {{ old('padding_top', $section->padding_top ?? '') === 'py-12' ? 'selected' : '' }}>Medium</option>
                                    <option value="py-16" {{ old('padding_top', $section->padding_top ?? 'py-16') === 'py-16' ? 'selected' : '' }}>Large</option>
                                    <option value="py-20" {{ old('padding_top', $section->padding_top ?? '') === 'py-20' ? 'selected' : '' }}>Extra Large</option>
                                    <option value="py-24" {{ old('padding_top', $section->padding_top ?? '') === 'py-24' ? 'selected' : '' }}>Huge</option>
                                    <option value="py-32" {{ old('padding_top', $section->padding_top ?? '') === 'py-32' ? 'selected' : '' }}>Maximum</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Padding Bottom</label>
                                <select name="padding_bottom" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="py-8" {{ old('padding_bottom', $section->padding_bottom ?? '') === 'py-8' ? 'selected' : '' }}>Small</option>
                                    <option value="py-12" {{ old('padding_bottom', $section->padding_bottom ?? '') === 'py-12' ? 'selected' : '' }}>Medium</option>
                                    <option value="py-16" {{ old('padding_bottom', $section->padding_bottom ?? 'py-16') === 'py-16' ? 'selected' : '' }}>Large</option>
                                    <option value="py-20" {{ old('padding_bottom', $section->padding_bottom ?? '') === 'py-20' ? 'selected' : '' }}>Extra Large</option>
                                    <option value="py-24" {{ old('padding_bottom', $section->padding_bottom ?? '') === 'py-24' ? 'selected' : '' }}>Huge</option>
                                    <option value="py-32" {{ old('padding_bottom', $section->padding_bottom ?? '') === 'py-32' ? 'selected' : '' }}>Maximum</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Margin Top</label>
                                <select name="margin_top" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="" {{ old('margin_top', $section->margin_top ?? '') === '' ? 'selected' : '' }}>None</option>
                                    <option value="mt-8" {{ old('margin_top', $section->margin_top ?? '') === 'mt-8' ? 'selected' : '' }}>Small</option>
                                    <option value="mt-12" {{ old('margin_top', $section->margin_top ?? '') === 'mt-12' ? 'selected' : '' }}>Medium</option>
                                    <option value="mt-16" {{ old('margin_top', $section->margin_top ?? '') === 'mt-16' ? 'selected' : '' }}>Large</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Margin Bottom</label>
                                <select name="margin_bottom" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="" {{ old('margin_bottom', $section->margin_bottom ?? '') === '' ? 'selected' : '' }}>None</option>
                                    <option value="mb-8" {{ old('margin_bottom', $section->margin_bottom ?? '') === 'mb-8' ? 'selected' : '' }}>Small</option>
                                    <option value="mb-12" {{ old('margin_bottom', $section->margin_bottom ?? '') === 'mb-12' ? 'selected' : '' }}>Medium</option>
                                    <option value="mb-16" {{ old('margin_bottom', $section->margin_bottom ?? '') === 'mb-16' ? 'selected' : '' }}>Large</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animation Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-animation">
                    <h2 class="text-lg font-semibold mb-4">Animation</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Animation Type</label>
                            <select name="animation_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach($animations as $key => $label)
                                    <option value="{{ $key }}" {{ old('animation_type', $section->animation_type ?? 'none') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Animation Delay (ms)</label>
                            <select name="animation_delay" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="0" {{ old('animation_delay', $section->animation_delay ?? '0') === '0' ? 'selected' : '' }}>No Delay</option>
                                <option value="100" {{ old('animation_delay', $section->animation_delay ?? '') === '100' ? 'selected' : '' }}>100ms</option>
                                <option value="200" {{ old('animation_delay', $section->animation_delay ?? '') === '200' ? 'selected' : '' }}>200ms</option>
                                <option value="300" {{ old('animation_delay', $section->animation_delay ?? '') === '300' ? 'selected' : '' }}>300ms</option>
                                <option value="500" {{ old('animation_delay', $section->animation_delay ?? '') === '500' ? 'selected' : '' }}>500ms</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Animation Duration (ms)</label>
                            <select name="animation_duration" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="300" {{ old('animation_duration', $section->animation_duration ?? '') === '300' ? 'selected' : '' }}>Fast (300ms)</option>
                                <option value="500" {{ old('animation_duration', $section->animation_duration ?? '500') === '500' ? 'selected' : '' }}>Normal (500ms)</option>
                                <option value="700" {{ old('animation_duration', $section->animation_duration ?? '') === '700' ? 'selected' : '' }}>Slow (700ms)</option>
                                <option value="1000" {{ old('animation_duration', $section->animation_duration ?? '') === '1000' ? 'selected' : '' }}>Very Slow (1000ms)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Items/Cards Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-items">
                    <h2 class="text-lg font-semibold mb-4">Items / Cards</h2>
                    <p class="text-gray-500 mb-4">Add items for card layouts, features, FAQs, etc.</p>

                    <!-- Card Styling -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Style</label>
                            <select name="card_style" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach($cardStyles as $key => $label)
                                    <option value="{{ $key }}" {{ old('card_style', $section->card_style ?? 'default') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hover Effect</label>
                            <select name="card_hover" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach($hoverEffects as $key => $label)
                                    <option value="{{ $key }}" {{ old('card_hover', $section->card_hover ?? 'none') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <label class="flex items-center">
                                <input type="checkbox" name="card_rounded" value="1"
                                       {{ old('card_rounded', $section->card_rounded ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Rounded Corners</span>
                            </label>
                        </div>
                    </div>

                    <!-- Dynamic Items -->
                    <div id="itemsContainer">
                        @php $items = old('items', $section->items ?? []); @endphp
                        @foreach($items as $index => $item)
                            <div class="item-card border border-gray-200 rounded-lg p-4 mb-4 relative" data-index="{{ $index }}">
                                <button type="button" onclick="removeItem(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome)</label>
                                        <input type="text" name="items[{{ $index }}][icon]" value="{{ $item['icon'] ?? '' }}"
                                               placeholder="fas fa-star"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <input type="text" name="items[{{ $index }}][title]" value="{{ $item['title'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    <textarea name="items[{{ $index }}][content]" rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ $item['content'] ?? '' }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                                        <input type="text" name="items[{{ $index }}][url]" value="{{ $item['url'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                        @if(!empty($item['image']))
                                            <div class="mb-2 flex items-center gap-2">
                                                <img src="{{ asset('storage/' . $item['image']) }}" class="h-12 w-12 object-cover rounded" id="item-preview-{{ $index }}">
                                                <input type="hidden" name="items[{{ $index }}][existing_image]" value="{{ $item['image'] }}">
                                                <button type="button" onclick="removeItemImage(this, {{ $index }})" class="text-red-500 hover:text-red-700 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                        <input type="file" name="item_images[{{ $index }}]" accept="image/*"
                                               onchange="previewItemImage(this, {{ $index }})"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <div id="new-item-preview-{{ $index }}" class="mt-2 hidden">
                                            <img src="" class="h-12 w-12 object-cover rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addItem()" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-blue-500 hover:text-blue-500">
                        <i class="fas fa-plus mr-2"></i> Add Item
                    </button>
                </div>

                <!-- Buttons Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-buttons">
                    <h2 class="text-lg font-semibold mb-4">Call to Action Buttons</h2>

                    <!-- Primary Button -->
                    <div class="p-4 bg-gray-50 rounded-lg mb-4">
                        <h3 class="font-medium mb-3">Primary Button</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="button_text" value="{{ old('button_text', $section->button_text ?? '') }}"
                                       placeholder="Get Started"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                                <input type="text" name="button_url" value="{{ old('button_url', $section->button_url ?? '') }}"
                                       placeholder="/contact"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Style</label>
                                <select name="button_style" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="primary" {{ old('button_style', $section->button_style ?? 'primary') === 'primary' ? 'selected' : '' }}>Primary (Filled)</option>
                                    <option value="secondary" {{ old('button_style', $section->button_style ?? '') === 'secondary' ? 'selected' : '' }}>Secondary</option>
                                    <option value="outline" {{ old('button_style', $section->button_style ?? '') === 'outline' ? 'selected' : '' }}>Outline</option>
                                    <option value="ghost" {{ old('button_style', $section->button_style ?? '') === 'ghost' ? 'selected' : '' }}>Ghost (Text Only)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Size</label>
                                <select name="button_size" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="sm" {{ old('button_size', $section->button_size ?? '') === 'sm' ? 'selected' : '' }}>Small</option>
                                    <option value="md" {{ old('button_size', $section->button_size ?? 'md') === 'md' ? 'selected' : '' }}>Medium</option>
                                    <option value="lg" {{ old('button_size', $section->button_size ?? '') === 'lg' ? 'selected' : '' }}>Large</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Secondary Button -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium mb-3">Secondary Button (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $section->secondary_button_text ?? '') }}"
                                       placeholder="Learn More"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                                <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $section->secondary_button_url ?? '') }}"
                                       placeholder="/about"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Tab -->
                <div class="tab-content bg-white rounded-xl shadow-sm p-6" id="tab-advanced">
                    <h2 class="text-lg font-semibold mb-4">Advanced Settings</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom CSS Class</label>
                            <input type="text" name="custom_class" value="{{ old('custom_class', $section->custom_class ?? '') }}"
                                   placeholder="my-custom-section"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom ID</label>
                            <input type="text" name="custom_id" value="{{ old('custom_id', $section->custom_id ?? '') }}"
                                   placeholder="section-about"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom CSS</label>
                        <textarea name="custom_css" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                  placeholder=".my-custom-section { /* styles */ }">{{ old('custom_css', $section->custom_css ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom JavaScript</label>
                        <textarea name="custom_js" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                  placeholder="// Your custom JavaScript">{{ old('custom_js', $section->custom_js ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <h2 class="text-lg font-semibold mb-4">Settings</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" name="order" value="{{ old('order', $section->order ?? 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_reusable" value="1"
                                       {{ old('is_reusable', $section->is_reusable ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Reusable (can be added to multiple pages)</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i> {{ isset($section) ? 'Update Section' : 'Create Section' }}
                        </button>

                        @if(isset($section))
                            <a href="{{ route('admin.sections.preview', $section) }}" target="_blank"
                               class="w-full mt-3 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-eye mr-2"></i> Preview
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function sectionForm() {
        return {
            layout: '{{ old('layout', $section->layout ?? 'default') }}',
            bgType: '{{ old('background_type', $section->background_type ?? 'color') }}'
        }
    }

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });

    // Dynamic items
    let itemIndex = {{ count(old('items', $section->items ?? [])) }};

    function addItem() {
        const container = document.getElementById('itemsContainer');
        const html = `
            <div class="item-card border border-gray-200 rounded-lg p-4 mb-4 relative" data-index="${itemIndex}">
                <button type="button" onclick="removeItem(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome)</label>
                        <input type="text" name="items[${itemIndex}][icon]" placeholder="fas fa-star"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="items[${itemIndex}][title]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="items[${itemIndex}][content]" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                        <input type="text" name="items[${itemIndex}][url]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="item_images[${itemIndex}]" accept="image/*"
                               onchange="previewItemImage(this, ${itemIndex})"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <div id="new-item-preview-${itemIndex}" class="mt-2 hidden">
                            <img src="" class="h-12 w-12 object-cover rounded">
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    // Preview item image before upload
    function previewItemImage(input, index) {
        const previewContainer = document.getElementById('new-item-preview-' + index);
        if (input.files && input.files[0] && previewContainer) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.classList.remove('hidden');
                previewContainer.querySelector('img').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove item image
    function removeItemImage(btn, index) {
        const container = btn.closest('.flex');
        const hiddenInput = container.querySelector('input[type="hidden"]');
        if (hiddenInput) {
            // Create a hidden input to mark image for deletion
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'items[' + index + '][delete_image]';
            deleteInput.value = '1';
            container.parentNode.appendChild(deleteInput);
        }
        container.remove();
    }

    function removeItem(btn) {
        btn.closest('.item-card').remove();
    }

    // Color picker sync
    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.addEventListener('input', function() {
            const textInput = this.nextElementSibling;
            if (textInput && textInput.tagName === 'INPUT') {
                textInput.value = this.value;
            }
        });
    });

    // Preview image from URL
    function previewImageUrl(inputId, previewContainerId, previewImgId) {
        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewImg = document.getElementById(previewImgId);

        if (!input || !previewContainer || !previewImg) {
            return;
        }

        const url = input.value.trim();

        if (!url) {
            alert('Please enter an image URL');
            return;
        }

        // Basic URL validation
        try {
            new URL(url);
        } catch (e) {
            alert('Please enter a valid URL');
            return;
        }

        // Show loading state
        previewContainer.classList.remove('hidden');
        previewImg.src = '';
        previewImg.alt = 'Loading...';

        // Create a new image to test loading
        const testImg = new Image();
        testImg.onload = function() {
            previewImg.src = url;
            previewImg.alt = 'Image Preview';
        };
        testImg.onerror = function() {
            previewContainer.classList.add('hidden');
            alert('Failed to load image. Please check the URL and ensure it points to a valid image.');
        };
        testImg.src = url;
    }
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#contentEditor'))
        .catch(error => console.error(error));
</script>
@endpush
