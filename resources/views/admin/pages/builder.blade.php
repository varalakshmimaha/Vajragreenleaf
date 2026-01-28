@extends('layouts.admin')

@section('title', 'Page Builder - ' . $page->title)

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Page Builder</h1>
            <p class="text-gray-600 mt-1">{{ $page->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ url($page->slug) }}" target="_blank" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-eye mr-2"></i> Preview
            </a>
            <a href="{{ route('admin.pages.edit', $page) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-cog mr-2"></i> Page Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Available Sections -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                <h2 class="text-lg font-semibold mb-4">Add Section</h2>
                <div class="space-y-2">
                    @foreach($sectionTypes as $type)
                        <form action="{{ route('admin.pages.sections.store', $page) }}" method="POST">
                            @csrf
                            <input type="hidden" name="section_type_id" value="{{ $type->id }}">
                            <input type="hidden" name="order" value="{{ $page->pageSections->count() + 1 }}">
                            <button type="submit" class="w-full text-left px-4 py-3 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-300 transition">
                                <i class="{{ $type->icon ?? 'fas fa-puzzle-piece' }} text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium">{{ $type->name }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Page Sections -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Page Sections</h2>

                @if($page->pageSections->count() > 0)
                    <div class="space-y-4" id="sections-list">
                        @foreach($page->pageSections->sortBy('order') as $section)
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" data-section-id="{{ $section->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="cursor-move text-gray-400 mr-3">
                                            <i class="fas fa-grip-vertical"></i>
                                        </span>
                                        <div>
                                            <span class="font-medium">{{ $section->sectionType->name ?? 'Unknown Section' }}</span>
                                            <span class="text-sm text-gray-500 ml-2">(Order: {{ $section->order }})</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="toggleSectionSettings({{ $section->id }})" class="text-blue-600 hover:text-blue-800 px-2 py-1">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <form action="{{ route('admin.pages.sections.toggle', [$page, $section]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="{{ $section->is_active ? 'text-green-600' : 'text-gray-400' }} hover:text-green-800 px-2 py-1">
                                                <i class="fas fa-{{ $section->is_active ? 'eye' : 'eye-slash' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pages.sections.destroy', [$page, $section]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this section?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 px-2 py-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Section Settings (Hidden by default) -->
                                <div id="section-settings-{{ $section->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                                    <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Title Override</label>
                                                <input type="text" name="settings[title]" value="{{ $section->settings['title'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle Override</label>
                                                <input type="text" name="settings[subtitle]" value="{{ $section->settings['subtitle'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                        </div>

                                        @if($section->sectionType && in_array($section->sectionType->slug, ['services', 'portfolio', 'blog', 'team']))
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Limit Items</label>
                                                    <input type="number" name="settings[limit]" value="{{ $section->settings['limit'] ?? '' }}" min="1" max="20"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                                <div class="flex items-center pt-6">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="settings[show_featured]" value="1" {{ ($section->settings['show_featured'] ?? false) ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-blue-600">
                                                        <span class="ml-2 text-sm text-gray-700">Show Featured Only</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        @if($section->sectionType && in_array($section->sectionType->slug, ['about', 'about-us', 'About']))
                                            <!-- Content -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                                <textarea name="settings[content]" rows="4"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ $section->settings['content'] ?? '' }}</textarea>
                                            </div>

                                            <!-- Description -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                <textarea name="settings[description]" rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Additional description text...">{{ $section->settings['description'] ?? '' }}</textarea>
                                            </div>

                                            <!-- Image Upload -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                                @if(!empty($section->settings['image']))
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $section->settings['image']) }}" alt="About Image" class="w-32 h-32 object-cover rounded-lg">
                                                        <label class="flex items-center mt-2">
                                                            <input type="checkbox" name="settings[remove_image]" value="1" class="rounded border-gray-300 text-red-600">
                                                            <span class="ml-2 text-sm text-red-600">Remove image</span>
                                                        </label>
                                                    </div>
                                                @endif
                                                <input type="file" name="settings[image]" accept="image/*"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                <p class="text-xs text-gray-500 mt-1">Max file size: 20MB</p>
                                            </div>

                                            <!-- Features (comma separated) -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Features (comma separated)</label>
                                                <input type="text" name="settings[features]" value="{{ is_array($section->settings['features'] ?? null) ? implode(', ', $section->settings['features']) : ($section->settings['features'] ?? '') }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Feature 1, Feature 2, Feature 3">
                                                <p class="text-xs text-gray-500 mt-1">Enter features separated by commas</p>
                                            </div>

                                            <!-- Button Settings -->
                                            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3">View More Button Settings</h4>
                                                <div class="mb-3">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="settings[show_button]" value="1" {{ ($section->settings['show_button'] ?? true) ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-blue-600">
                                                        <span class="ml-2 text-sm text-gray-700">Show View More Button</span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                                                        <input type="text" name="settings[button_text]" value="{{ $section->settings['button_text'] ?? 'Learn More About Us' }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                                                        <input type="text" name="settings[button_url]" value="{{ $section->settings['button_url'] ?? '' }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="/about-us">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($section->sectionType && $section->sectionType->slug === 'cta')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                                                    <input type="text" name="settings[cta_text]" value="{{ $section->settings['cta_text'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button URL</label>
                                                    <input type="text" name="settings[cta_url]" value="{{ $section->settings['cta_url'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                            <input type="number" name="order" value="{{ $section->order }}" min="1"
                                                class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                                Save Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-puzzle-piece text-4xl mb-4"></i>
                        <p>No sections added yet.</p>
                        <p class="text-sm">Use the panel on the left to add sections.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleSectionSettings(id) {
        const settings = document.getElementById('section-settings-' + id);
        settings.classList.toggle('hidden');
    }
</script>
@endpush
