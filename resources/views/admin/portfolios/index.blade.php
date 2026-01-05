@extends('layouts.admin')

@section('title', 'Portfolio')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Portfolio</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.portfolios.categories') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-folder mr-2"></i> Categories
            </a>
            <a href="{{ route('admin.portfolios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Project
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($portfolios as $portfolio)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($portfolio->featured_image)
                                    <img src="{{ asset('storage/' . $portfolio->featured_image) }}" class="h-10 w-10 rounded object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $portfolio->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($portfolio->short_description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($portfolio->category)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $portfolio->category->name }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            {{ $portfolio->client_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($portfolio->is_featured)
                                <i class="fas fa-star text-yellow-500"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($portfolio->is_active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.portfolios.destroy', $portfolio) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No portfolio items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($portfolios->hasPages())
        <div class="mt-6">
            {{ $portfolios->links() }}
        </div>
    @endif
@endsection
