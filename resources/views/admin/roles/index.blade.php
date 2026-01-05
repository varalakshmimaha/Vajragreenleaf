@extends('layouts.admin')

@section('title', 'Manage Roles')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Roles</h1>
            <p class="text-gray-600">Manage user roles and their permissions</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Role
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden {{ $role->is_super_admin ? 'ring-2 ring-purple-500' : '' }}">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                {{ $role->name }}
                                @if($role->is_super_admin)
                                    <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                        Super Admin
                                    </span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $role->slug }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm rounded-full {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($role->description)
                        <p class="text-gray-600 text-sm mb-4">{{ $role->description }}</p>
                    @endif

                    <div class="flex items-center gap-6 text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $role->users_count }} Users</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-key mr-2"></i>
                            <span>{{ $role->is_super_admin ? 'All' : $role->permissions_count }} Permissions</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t">
                        <a href="{{ route('admin.roles.edit', $role) }}"
                           class="flex-1 text-center py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        @if(!$role->is_super_admin)
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-xl shadow-sm">
                <i class="fas fa-user-tag text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No roles found.</p>
                <a href="{{ route('admin.roles.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    Create your first role
                </a>
            </div>
        @endforelse
    </div>

    @if($roles->hasPages())
        <div class="mt-6">
            {{ $roles->links() }}
        </div>
    @endif
@endsection
