@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Service' : 'Create Service')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($service) ? 'Edit Service' : 'Create Service' }}</h1>
    </div>

    <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($service))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Service Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                            <input type="text" name="slug" value="{{ old('slug', $service->slug ?? '') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class (Font Awesome)</label>
                        <input type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}" placeholder="fa-solid fa-code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                        <textarea name="description" rows="8" id="description-editor"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $service->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Images</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image</label>
                            @if(isset($service) && $service->banner_image)
                                <img src="{{ asset('storage/' . $service->banner_image) }}" class="h-24 mb-2 rounded">
                            @endif
                            <input type="file" name="banner_image" accept="image/*" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                            @if(isset($service) && $service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" class="h-24 mb-2 rounded">
                            @endif
                            <input type="file" name="image" accept="image/*" class="w-full">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">SEO Settings</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('meta_description', $service->meta_description ?? '') }}</textarea>
                    </div>
                </div>

                @if(isset($service))
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Service Plans</h2>
                            <button type="button" onclick="addPlan()" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Plan
                            </button>
                        </div>

                        <div id="plans-container">
                            @foreach($service->plans ?? [] as $index => $plan)
                                <div class="plan-item border border-gray-200 rounded-lg p-4 mb-4">
                                    <input type="hidden" name="plans[{{ $index }}][id]" value="{{ $plan->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                                            <input type="text" name="plans[{{ $index }}][name]" value="{{ $plan->name }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                            <input type="number" name="plans[{{ $index }}][price]" value="{{ $plan->price }}" step="0.01"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Label</label>
                                            <input type="text" name="plans[{{ $index }}][price_label]" value="{{ $plan->price_label }}" placeholder="/month"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Features (one per line)</label>
                                        <textarea name="plans[{{ $index }}][features_text]" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ is_array($plan->features) ? implode("\n", $plan->features) : '' }}</textarea>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="plans[{{ $index }}][is_popular]" value="1" {{ $plan->is_popular ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600">
                                            <span class="ml-2 text-sm text-gray-700">Popular Plan</span>
                                        </label>
                                        <button type="button" onclick="this.closest('.plan-item').remove()" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Publish</h2>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Featured</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <input type="number" name="order" value="{{ old('order', $service->order ?? 0) }}" min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            {{ isset($service) ? 'Update' : 'Create' }}
                        </button>
                        <a href="{{ route('admin.services.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#description-editor'))
        .catch(error => console.error(error));

    let planIndex = {{ isset($service) ? $service->plans->count() : 0 }};

    function addPlan() {
        const container = document.getElementById('plans-container');
        const html = `
            <div class="plan-item border border-gray-200 rounded-lg p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                        <input type="text" name="plans[${planIndex}][name]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" name="plans[${planIndex}][price]" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Label</label>
                        <input type="text" name="plans[${planIndex}][price_label]" placeholder="/month"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Features (one per line)</label>
                    <textarea name="plans[${planIndex}][features_text]" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="plans[${planIndex}][is_popular]" value="1"
                            class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Popular Plan</span>
                    </label>
                    <button type="button" onclick="this.closest('.plan-item').remove()" class="text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-trash mr-1"></i> Remove
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        planIndex++;
    }
</script>
@endpush
