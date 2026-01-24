@extends('layouts.admin')

@section('title', isset($certification) ? 'Edit Certification' : 'Create Certification')

@section('content')
    <div class="mb-8 text-white">
        <h1 class="text-3xl font-bold text-gray-900">{{ isset($certification) ? 'Edit Certification' : 'Create Certification' }}</h1>
    </div>

    <form action="{{ isset($certification) ? route('admin.certifications.update', $certification) : route('admin.certifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($certification))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Certification Details</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certification Title *</label>
                        <input type="text" name="title" value="{{ old('title', $certification->title ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Issuing Authority *</label>
                        <input type="text" name="issuing_authority" value="{{ old('issuing_authority', $certification->issuing_authority ?? '') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" name="certificate_number" value="{{ old('certificate_number', $certification->certificate_number ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', $certification->order ?? 0) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date</label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', isset($certification->issue_date) ? $certification->issue_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date', isset($certification->expiry_date) ? $certification->expiry_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $certification->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Certification Image</h2>
                    <div class="mb-4">
                        @if(isset($certification) && $certification->image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $certification->image) }}" class="h-48 rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*,.pdf" class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Upload JPG, PNG or PDF (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Status</h2>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $certification->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-bold">
                            {{ isset($certification) ? 'Update Certification' : 'Save Certification' }}
                        </button>
                        <a href="{{ route('admin.certifications.index') }}" class="w-full text-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
