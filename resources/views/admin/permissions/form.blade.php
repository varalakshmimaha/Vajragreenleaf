@extends('layouts.admin')

@section('title', isset($permission) ? 'Edit Permission' : 'Create Permission')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($permission) ? 'Edit Permission' : 'Create Permission' }}</h1>
            <p class="text-gray-600">{{ isset($permission) ? 'Update permission details' : 'Add a new permission' }}</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Permissions
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

    <div class="max-w-2xl">
        <form action="{{ isset($permission) ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}" method="POST">
            @csrf
            @if(isset($permission))
                @method('PUT')
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                    <input type="text" name="name" value="{{ old('name', $permission->name ?? '') }}" required
                           placeholder="e.g., View Dashboard"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $permission->slug ?? '') }}"
                           placeholder="e.g., dashboard.view (auto-generated if empty)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Unique identifier used in code. Use dots to separate (e.g., users.create)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group *</label>
                    <div class="flex gap-2">
                        <select name="group" id="group-select" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select or type new group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ old('group', $permission->group ?? '') === $group ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $group)) }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" id="new-group" placeholder="New group name"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               onchange="document.getElementById('group-select').value = this.value.toLowerCase().replace(/\s+/g, '-')">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Used to organize permissions in the UI</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Brief description of what this permission allows"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $permission->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                               class="rounded border-gray-300 text-blue-600"
                               {{ old('is_active', $permission->is_active ?? true) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Active</span>
                    </label>
                </div>

                <div class="pt-4 border-t flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-save mr-2"></i> {{ isset($permission) ? 'Update Permission' : 'Create Permission' }}
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
