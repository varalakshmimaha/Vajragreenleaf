@extends('layouts.admin')

@section('title', 'Certifications')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Certifications</h1>
        <a href="{{ route('admin.certifications.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Certification
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.certifications.index') }}" method="GET" class="flex-1 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search certifications..." 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-gray-100 px-4 py-2 rounded-lg hover:bg-gray-200">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="flex items-center gap-2">
            <select id="bulk-action" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Bulk Actions</option>
                <option value="activate">Activate Selected</option>
                <option value="deactivate">Deactivate Selected</option>
                <option value="delete">Delete Selected</option>
            </select>
            <button onclick="applyBulkAction()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Apply</button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certification</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Authority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($certifications as $cert)
                    <tr>
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selected[]" value="{{ $cert->id }}" class="cert-checkbox rounded border-gray-300 text-blue-600">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($cert->image)
                                    <img src="{{ asset('storage/' . $cert->image) }}" class="h-10 w-10 object-cover rounded mr-3">
                                @else
                                    <div class="h-10 w-10 bg-gray-100 flex items-center justify-center rounded mr-3">
                                        <i class="fas fa-certificate text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $cert->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $cert->certificate_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cert->issuing_authority }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cert->issue_date ? $cert->issue_date->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($cert->is_active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.certifications.edit', $cert) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.certifications.destroy', $cert) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No certifications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($certifications->hasPages())
        <div class="mt-6">
            {{ $certifications->links() }}
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('.cert-checkbox').forEach(cb => cb.checked = e.target.checked);
    });

    function applyBulkAction() {
        const action = document.getElementById('bulk-action').value;
        const selected = Array.from(document.querySelectorAll('.cert-checkbox:checked')).map(cb => cb.value);

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (selected.length === 0) {
            alert('Please select at least one item');
            return;
        }

        if (!confirm(`Are you sure you want to ${action} selected items?`)) {
            return;
        }

        // We can use a hidden form or fetch
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.certifications.index') }}/bulk-action"; // I'll add this route
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        selected.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
