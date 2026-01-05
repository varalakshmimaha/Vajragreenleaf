@extends('layouts.admin')

@section('title', 'Enquiries')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Enquiries</h1>

        <!-- Tabs -->
        <div class="flex space-x-4 mt-6 border-b">
            <a href="{{ route('admin.enquiries.index', ['type' => 'contact']) }}"
               class="px-4 py-2 border-b-2 {{ $type == 'contact' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Contact Submissions
            </a>
            <a href="{{ route('admin.enquiries.index', ['type' => 'service']) }}"
               class="px-4 py-2 border-b-2 {{ $type == 'service' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Service Enquiries
            </a>
            <a href="{{ route('admin.enquiries.index', ['type' => 'product']) }}"
               class="px-4 py-2 border-b-2 {{ $type == 'product' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Product Enquiries
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
                    @if($type == 'contact')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    @else
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $type == 'service' ? 'Service' : 'Product' }}</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($enquiries as $enquiry)
                    <tr class="{{ $enquiry->status == 'pending' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4">
                            <div>
                                @if($type == 'contact')
                                    <div class="font-medium text-gray-900">{{ $enquiry->name }}</div>
                                @endif
                                <div class="text-sm text-gray-500">{{ $enquiry->email ?? $enquiry->mobile }}</div>
                                @if($enquiry->mobile && $type == 'contact')
                                    <div class="text-sm text-gray-500">{{ $enquiry->mobile }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($type == 'contact')
                                <span class="text-gray-900">{{ Str::limit($enquiry->subject, 30) }}</span>
                            @elseif($type == 'service')
                                <span class="text-gray-900">{{ $enquiry->service->name ?? 'N/A' }}</span>
                                @if($enquiry->servicePlan)
                                    <br><span class="text-sm text-gray-500">{{ $enquiry->servicePlan->name }}</span>
                                @endif
                            @else
                                <span class="text-gray-900">{{ $enquiry->product->name ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'contacted' => 'bg-blue-100 text-blue-800',
                                    'converted' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs {{ $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-800' }} rounded-full">
                                {{ ucfirst($enquiry->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm">
                            {{ $enquiry->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="viewEnquiry({{ $enquiry->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <form action="{{ route('admin.enquiries.destroy', ['type' => $type, 'id' => $enquiry->id]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this enquiry?')">
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No enquiries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($enquiries->hasPages())
        <div class="mt-6">
            {{ $enquiries->links() }}
        </div>
    @endif

    <!-- View Modal -->
    <div id="enquiry-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-semibold">Enquiry Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modal-content">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function viewEnquiry(id) {
        document.getElementById('enquiry-modal').classList.remove('hidden');
        document.getElementById('modal-content').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

        fetch(`/admin/enquiries/{{ $type }}/${id}`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            ${data.name ? `<div><label class="text-sm text-gray-500">Name</label><p class="font-medium">${data.name}</p></div>` : ''}
                            <div><label class="text-sm text-gray-500">Email</label><p class="font-medium">${data.email || data.mobile}</p></div>
                            ${data.mobile ? `<div><label class="text-sm text-gray-500">Phone</label><p class="font-medium">${data.mobile}</p></div>` : ''}
                            ${data.subject ? `<div class="col-span-2"><label class="text-sm text-gray-500">Subject</label><p class="font-medium">${data.subject}</p></div>` : ''}
                        </div>
                        ${data.message || data.notes ? `<div><label class="text-sm text-gray-500">Message</label><p class="mt-1 text-gray-700">${data.message || data.notes}</p></div>` : ''}
                        <div class="pt-4 border-t">
                            <label class="text-sm text-gray-500">Update Status</label>
                            <form action="/admin/enquiries/{{ $type }}/${id}/status" method="POST" class="flex items-center space-x-2 mt-2">
                                @csrf
                                <select name="status" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="contacted" ${data.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                                    <option value="converted" ${data.status === 'converted' ? 'selected' : ''}>Converted</option>
                                    <option value="closed" ${data.status === 'closed' ? 'selected' : ''}>Closed</option>
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Update</button>
                            </form>
                        </div>
                    </div>
                `;
                document.getElementById('modal-content').innerHTML = html;
            });
    }

    function closeModal() {
        document.getElementById('enquiry-modal').classList.add('hidden');
    }

    document.getElementById('enquiry-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush
