@extends('layouts.admin')

@section('title', 'Manage Customers')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
            <p class="text-gray-600">Website registered users</p>
        </div>
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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, mobile, referral ID..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">
                <i class="fas fa-search mr-2"></i> Filter
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Customer</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Mobile</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Referral ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sponsor</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Registered</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900 font-medium">{{ $customer->mobile }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($customer->referral_id)
                                <span class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg font-mono font-bold text-sm">
                                    <i class="fas fa-hashtag mr-1 text-xs"></i>
                                    {{ $customer->referral_id }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">Not assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($customer->sponsor_referral_id)
                                @php
                                    $sponsor = $customer->sponsorByReferralId;
                                @endphp
                                <div class="text-sm">
                                    <div class="font-mono font-bold text-purple-700">{{ $customer->sponsor_referral_id }}</div>
                                    @if($sponsor)
                                        <div class="text-gray-600 text-xs">{{ $sponsor->name }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">No sponsor</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" onclick="toggleCustomerStatus({{ $customer->id }})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    id="status-{{ $customer->id }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $customer->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="View Details & Referral Network">
                                    <i class="fas fa-network-wired"></i>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>No customers found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($customers->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function toggleCustomerStatus(customerId) {
    fetch(`/admin/customers/${customerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else if (data.error) {
            alert(data.error);
        }
    });
}
</script>
@endpush
