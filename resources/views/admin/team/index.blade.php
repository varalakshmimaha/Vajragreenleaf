@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Team Members</h1>
        <a href="{{ route('admin.team.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Member
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($team as $member)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="aspect-square bg-gray-100">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-user text-6xl text-gray-300"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900">{{ $member->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $member->designation }}</p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="px-2 py-1 text-xs {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.team.edit', $member) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.team.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                No team members found.
            </div>
        @endforelse
    </div>

    @if($team->hasPages())
        <div class="mt-6">
            {{ $team->links() }}
        </div>
    @endif
@endsection
