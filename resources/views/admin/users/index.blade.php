@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Users</h1>
            <p class="text-gray-600">Manage admin users and their access</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add User
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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900">
                <i class="fas fa-search mr-2"></i> Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User Name</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Mobile</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sponsor ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Address</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $user->mobile ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4" id="sponsor-{{ $user->id }}">
                            @if($user->username)
                                <button onclick="viewSponsorInfo({{ $user->id }}, '{{ $user->username }}')" 
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition font-mono font-bold text-sm">
                                    <i class="fas fa-id-badge"></i>
                                    <span>{{ $user->username }}</span>
                                </button>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                @if($user->city && $user->state)
                                    {{ $user->city }}, {{ $user->state }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" onclick="toggleUserStatus({{ $user->id }})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    id="status-{{ $user->id }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <span class="w-2 h-2 rounded-full mr-2 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>No users found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function toggleUserStatus(userId) {
    fetch(`/admin/users/${userId}/toggle-status`, {
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

function viewSponsorInfo(userId, sponsorId) {
    window.location.href = `/admin/users/${userId}`;
}
</script>
@endpush

@push('scripts')
<script>
// Poll for updates every 8 seconds and update sponsor cells
(function() {
    const interval = 8000;
    async function fetchUpdates() {
        try {
            const res = await fetch(window.location.pathname + '?ajax=1', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const json = await res.json();
            if (!json.data) return;
            json.data.forEach(u => {
                const el = document.getElementById('sponsor-' + u.id);
                if (!el) return;
                // Decide displayed value: sponsor_username || sponsor_id || username || em dash
                const val = u.sponsor_username || u.sponsor_id || u.username || '—';
                // Update inner text of the primary span
                const span = el.querySelector('span');
                if (span && span.textContent !== val) {
                    span.textContent = val;
                }
            });
        } catch (e) {
            // ignore
        }
    }

    // Start polling after short delay
    setTimeout(() => {
        fetchUpdates();
        setInterval(fetchUpdates, interval);
    }, 2000);
})();
</script>
@endpush
