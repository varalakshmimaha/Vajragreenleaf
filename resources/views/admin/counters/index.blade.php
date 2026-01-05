@extends('layouts.admin')

@section('title', 'Counters')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Counters</h1>
        <button onclick="document.getElementById('addCounterModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Counter
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($counters as $counter)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
                    @if($counter->icon)
                        <i class="{{ $counter->icon }} text-blue-600 text-xl"></i>
                    @else
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($counter->value) }}{{ $counter->suffix }}</h3>
                <p class="text-gray-600 mt-1">{{ $counter->title }}</p>
                <div class="flex items-center justify-between mt-4 pt-4 border-t">
                    <span class="px-2 py-1 text-xs {{ $counter->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                        {{ $counter->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="editCounter({{ $counter->id }})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.counters.destroy', $counter) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <i class="fas fa-chart-bar text-6xl mb-4"></i>
                <p class="text-xl">No counters found.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Counter Modal -->
    <div id="addCounterModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md m-4">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Add New Counter</h2>
                    <button onclick="document.getElementById('addCounterModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.counters.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <input type="number" name="value" required min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Suffix (e.g., +, %, K)</label>
                        <input type="text" name="suffix" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" placeholder="fas fa-users" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked id="addCounterActive" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="addCounterActive" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addCounterModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Counter</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($counters as $counter)
    <!-- Edit Counter Modal -->
    <div id="editCounterModal{{ $counter->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md m-4">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Edit Counter</h2>
                    <button onclick="document.getElementById('editCounterModal{{ $counter->id }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.counters.update', $counter) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ $counter->title }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <input type="number" name="value" value="{{ $counter->value }}" required min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                        <input type="text" name="suffix" value="{{ $counter->suffix }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                        <input type="text" name="icon" value="{{ $counter->icon }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ $counter->is_active ? 'checked' : '' }} id="editCounterActive{{ $counter->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="editCounterActive{{ $counter->id }}" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editCounterModal{{ $counter->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Counter</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <script>
        function editCounter(id) {
            document.getElementById('editCounterModal' + id).classList.remove('hidden');
        }
    </script>
@endsection
