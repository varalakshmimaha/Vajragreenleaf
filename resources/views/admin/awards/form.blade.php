@extends('layouts.admin')

@section('title', isset($award) ? 'Edit Award' : 'Create Award')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($award) ? 'Edit Award' : 'Create Award' }}</h1>
    </div>

    <form action="{{ isset($award) ? route('admin.awards.update', $award) : route('admin.awards.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($award))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Award Details</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Award Title *</label>
                        <input type="text" name="title" value="{{ old('title', $award->title ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Awarding Organization *</label>
                            <input type="text" name="awarding_organization" value="{{ old('awarding_organization', $award->awarding_organization ?? '') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <input type="number" name="year" value="{{ old('year', $award->year ?? date('Y')) }}" placeholder="YYYY"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="Excellence" {{ (old('category', $award->category ?? '') == 'Excellence') ? 'selected' : '' }}>Excellence</option>
                                <option value="Leadership" {{ (old('category', $award->category ?? '') == 'Leadership') ? 'selected' : '' }}>Leadership</option>
                                <option value="Growth" {{ (old('category', $award->category ?? '') == 'Growth') ? 'selected' : '' }}>Growth</option>
                                <option value="Innovation" {{ (old('category', $award->category ?? '') == 'Innovation') ? 'selected' : '' }}>Innovation</option>
                                <option value="Other" {{ (old('category', $award->category ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', $award->order ?? 0) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $award->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Award Image</h2>
                    <div class="mb-4">
                        @if(isset($award) && $award->image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $award->image) }}" class="h-48 rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Upload JPG or PNG (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Status</h2>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $award->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-bold">
                            {{ isset($award) ? 'Update Award' : 'Save Award' }}
                        </button>
                        <a href="{{ route('admin.awards.index') }}" class="w-full text-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
