@extends('layouts.admin')

@section('title', isset($role) ? 'Edit Role' : 'Create Role')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h1>
            <p class="text-gray-600">{{ isset($role) ? 'Update role settings and permissions' : 'Create a new role with specific permissions' }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Roles
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

    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                            <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $role->slug ?? '') }}"
                                   placeholder="Auto-generated if left empty"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Unique identifier for the role (e.g., content-editor)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $role->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Permissions</h2>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="selectAllPermissions()"
                                    class="text-blue-600 text-sm hover:underline">Select All</button>
                            <button type="button" onclick="deselectAllPermissions()"
                                    class="text-red-600 text-sm hover:underline">Deselect All</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 flex items-center justify-between">
                                    <span class="font-medium text-gray-900 capitalize">{{ str_replace('-', ' ', $group) }}</span>
                                    <button type="button" onclick="togglePermissionGroup('{{ $group }}')"
                                            class="text-blue-600 text-xs hover:underline">Toggle All</button>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="group-{{ $group }}">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-start p-2 hover:bg-gray-50 rounded cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                   class="mt-1 rounded border-gray-300 text-blue-600 permission-checkbox permission-{{ $group }}"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                                            <div class="ml-3">
                                                <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @if($permissions->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-key text-4xl mb-4"></i>
                                <p>No permissions available.</p>
                                <a href="{{ route('admin.permissions.seed') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                    Seed default permissions
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Options -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Options</h2>

                    <div class="space-y-4">
                        <label class="flex items-start p-3 border border-purple-200 bg-purple-50 rounded-lg cursor-pointer">
                            <input type="checkbox" name="is_super_admin" value="1"
                                   class="mt-1 rounded border-gray-300 text-purple-600"
                                   {{ old('is_super_admin', $role->is_super_admin ?? false) ? 'checked' : '' }}
                                   {{ isset($role) && $role->is_super_admin ? 'disabled' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">Super Admin</span>
                                <p class="text-xs text-gray-500">Has access to all permissions automatically</p>
                            </div>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   class="rounded border-gray-300 text-blue-600"
                                   {{ old('is_active', $role->is_active ?? true) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-save mr-2"></i> {{ isset($role) ? 'Update Role' : 'Create Role' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
function togglePermissionGroup(group) {
    const checkboxes = document.querySelectorAll(`.permission-${group}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
}

function deselectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
}
</script>
@endpush
