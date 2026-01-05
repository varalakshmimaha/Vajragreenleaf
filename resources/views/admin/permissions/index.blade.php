@extends('layouts.admin')

@section('title', 'Manage Permissions')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Permissions</h1>
            <p class="text-gray-600">Manage system permissions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.permissions.seed') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-database mr-2"></i> Seed Defaults
            </a>
            <a href="{{ route('admin.permissions.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Permission
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search permissions..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-48">
                <select name="group" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $group)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900">
                <i class="fas fa-search mr-2"></i> Filter
            </button>
            <a href="{{ route('admin.permissions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    <!-- Permissions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Permission</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Slug</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Group</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Used By</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($permissions as $permission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $permission->name }}</div>
                            @if($permission->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($permission->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <code class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $permission->slug }}</code>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm capitalize">
                                {{ str_replace('-', ' ', $permission->group) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $permission->roles_count }} roles
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm {{ $permission->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $permission->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.permissions.edit', $permission) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-key text-4xl mb-4"></i>
                            <p>No permissions found.</p>
                            <a href="{{ route('admin.permissions.seed') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                Seed default permissions
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($permissions->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $permissions->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
