@extends('layouts.admin')

@section('title', 'Careers / Job Postings')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Career / Job Postings</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.careers.applications') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 flex items-center">
                <i class="fas fa-file-alt mr-2"></i> Applications
            </a>
            <a href="{{ route('admin.careers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Job Posting
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($careers as $career)
                    <tr>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-gray-900 flex items-center">
                                    {{ $career->title }}
                                    @if($career->is_featured)
                                        <i class="fas fa-star text-yellow-500 ml-2" title="Featured"></i>
                                    @endif
                                </div>
                                @if($career->department)
                                    <div class="text-sm text-gray-500">{{ $career->department }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-gray-900"><i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>{{ $career->location }}</div>
                                <div class="text-gray-500"><i class="fas fa-clock mr-1 text-gray-400"></i>{{ $career->getJobTypeLabel() }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.careers.applications', $career) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200">
                                <i class="fas fa-users mr-1"></i>{{ $career->applications_count }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($career->is_active)
                                @if($career->isOpen())
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Open</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">Closed</span>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('careers.show', $career->slug) }}" target="_blank" class="text-gray-600 hover:text-gray-900 mr-3">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ route('admin.careers.edit', $career) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.careers.destroy', $career) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will also delete all applications.')">
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No job postings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($careers->hasPages())
        <div class="mt-6">
            {{ $careers->links() }}
        </div>
    @endif
@endsection
