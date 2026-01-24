@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Create User')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>
            <p class="text-gray-600">{{ isset($user) ? 'Update user details and permissions' : 'Add a new admin user' }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users
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

    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password {{ isset($user) ? '(leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                @if(!isset($user))
                <!-- Roles -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Assign Roles</h2>
                    <p class="text-sm text-gray-500 mb-4">Select roles that define user's base permissions</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:border-blue-300 transition-colors {{ $role->is_super_admin ? 'border-purple-200 bg-purple-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                       class="mt-1 rounded border-gray-300 text-blue-600"
                                       {{ in_array($role->id, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900 flex items-center">
                                        {{ $role->name }}
                                        @if($role->is_super_admin)
                                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs rounded">Super Admin</span>
                                        @endif
                                    </div>
                                    @if($role->description)
                                        <p class="text-sm text-gray-500">{{ $role->description }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!isset($user))
                <!-- Direct Permissions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Permissions</h2>
                    <p class="text-sm text-gray-500 mb-4">Grant additional permissions beyond role-based access</p>

                    <div class="space-y-6">
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 font-medium text-gray-900 capitalize flex items-center justify-between">
                                    <span>{{ str_replace('-', ' ', $group) }}</span>
                                    <button type="button" onclick="togglePermissionGroup('{{ $group }}')"
                                            class="text-blue-600 text-sm hover:underline">
                                        Toggle All
                                    </button>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="group-{{ $group }}">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                   class="rounded border-gray-300 text-blue-600 permission-{{ $group }}"
                                                   {{ in_array($permission->id, old('permissions', $userPermissions ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Avatar -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h2>

                    <div class="text-center">
                        @if(isset($user) && $user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" id="avatar-preview">
                            <label class="flex items-center justify-center mb-4">
                                <input type="checkbox" name="remove_avatar" class="rounded border-gray-300 text-red-600">
                                <span class="ml-2 text-sm text-gray-700">Remove current photo</span>
                            </label>
                        @else
                            <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center" id="avatar-preview">
                                <i class="fas fa-user text-4xl text-gray-400"></i>
                            </div>
                        @endif

                        <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden"
                               onchange="previewAvatar(this)">
                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-camera mr-1"></i> Upload Photo
                        </button>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status</h2>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                               class="rounded border-gray-300 text-blue-600"
                               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Active</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-2">Inactive users cannot log in to the admin panel.</p>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-save mr-2"></i> {{ isset($user) ? 'Update User' : 'Create User' }}
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

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.outerHTML = `<img src="${e.target.result}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" id="avatar-preview">`;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
