@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Clients</h1>
        <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Client
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        @forelse($clients as $client)
            <div class="bg-white rounded-xl shadow-sm p-4 text-center">
                <div class="h-20 flex items-center justify-center mb-3">
                    @if($client->logo)
                        <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="max-h-full max-w-full object-contain">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-2xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <h3 class="font-medium text-gray-900 text-sm truncate">{{ $client->name }}</h3>
                @if($client->website)
                    <a href="{{ $client->website }}" target="_blank" class="text-xs text-blue-600 hover:underline">Visit Website</a>
                @endif
                <div class="flex items-center justify-center mt-3 pt-3 border-t space-x-2">
                    <span class="px-2 py-0.5 text-xs {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <button onclick="editClient({{ $client->id }})" class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <i class="fas fa-building text-6xl mb-4"></i>
                <p class="text-xl">No clients found.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Client Modal -->
    <div id="addClientModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md m-4">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Add New Client</h2>
                    <button onclick="document.getElementById('addClientModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                        <input type="url" name="website" placeholder="https://" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked id="addClientActive" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="addClientActive" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addClientModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Client</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($clients as $client)
    <!-- Edit Client Modal -->
    <div id="editClientModal{{ $client->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md m-4">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Edit Client</h2>
                    <button onclick="document.getElementById('editClientModal{{ $client->id }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.clients.update', $client) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <input type="text" name="name" value="{{ $client->name }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                        @if($client->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $client->logo) }}" class="h-12 object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                        <input type="url" name="website" value="{{ $client->website }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ $client->is_active ? 'checked' : '' }} id="editClientActive{{ $client->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="editClientActive{{ $client->id }}" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editClientModal{{ $client->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Client</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <script>
        function editClient(id) {
            document.getElementById('editClientModal' + id).classList.remove('hidden');
        }
    </script>
@endsection
