@extends('layouts.admin')

@section('title', isset($theme) ? 'Edit Theme' : 'Create Theme')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($theme) ? 'Edit Theme' : 'Create Theme' }}</h1>
    </div>

    <form action="{{ isset($theme) ? route('admin.themes.update', $theme) : route('admin.themes.store') }}" method="POST">
        @csrf
        @if(isset($theme))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Theme Details</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme Name *</label>
                        <input type="text" name="name" value="{{ old('name', $theme->name ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Colors</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="primary_color" value="{{ old('primary_color', $theme->primary_color ?? '#2563eb') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('primary_color', $theme->primary_color ?? '#2563eb') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="secondary_color" value="{{ old('secondary_color', $theme->secondary_color ?? '#1e40af') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('secondary_color', $theme->secondary_color ?? '#1e40af') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="accent_color" value="{{ old('accent_color', $theme->accent_color ?? '#f59e0b') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('accent_color', $theme->accent_color ?? '#f59e0b') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="text_color" value="{{ old('text_color', $theme->text_color ?? '#1f2937') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('text_color', $theme->text_color ?? '#1f2937') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heading Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="heading_color" value="{{ old('heading_color', $theme->heading_color ?? '#111827') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('heading_color', $theme->heading_color ?? '#111827') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="background_color" value="{{ old('background_color', $theme->background_color ?? '#ffffff') }}"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                                <input type="text" value="{{ old('background_color', $theme->background_color ?? '#ffffff') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Typography</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heading Font</label>
                            <select name="heading_font" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach(['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Montserrat', 'Playfair Display', 'Merriweather'] as $font)
                                    <option value="{{ $font }}" {{ old('heading_font', $theme->heading_font ?? 'Inter') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Body Font</label>
                            <select name="body_font" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach(['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato', 'Source Sans Pro', 'Nunito'] as $font)
                                    <option value="{{ $font }}" {{ old('body_font', $theme->body_font ?? 'Inter') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Preview</h2>

                    <div id="theme-preview" class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="h-24 rounded-lg mb-4" style="background: linear-gradient(135deg, {{ $theme->primary_color ?? '#2563eb' }} 0%, {{ $theme->secondary_color ?? '#1e40af' }} 100%);"></div>
                        <h3 class="text-lg font-bold mb-2" style="color: {{ $theme->heading_color ?? '#111827' }};">Heading Text</h3>
                        <p class="text-sm mb-2" style="color: {{ $theme->text_color ?? '#1f2937' }};">Body text example with the selected colors and fonts.</p>
                        <button class="px-4 py-2 rounded text-white text-sm" style="background-color: {{ $theme->primary_color ?? '#2563eb' }};">Primary Button</button>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Publish</h2>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $theme->is_active ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Set as Active Theme</span>
                        </label>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            {{ isset($theme) ? 'Update' : 'Create' }}
                        </button>
                        <a href="{{ route('admin.themes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Sync color inputs with text displays
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        colorInput.addEventListener('input', function() {
            this.nextElementSibling.value = this.value;
        });
    });
</script>
@endpush
