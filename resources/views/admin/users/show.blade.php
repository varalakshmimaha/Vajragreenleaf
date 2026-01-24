@extends('layouts.admin')

@section('title', 'View User')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="text-gray-600">View user information and activity</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-full mx-auto object-cover mb-4 border-4 border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-500 mb-4">{{ $user->email }}</p>
                    
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <span class="w-2 h-2 rounded-full mr-2 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Username</label>
                            <p class="text-gray-900 font-medium">{{ $user->username ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $user->phone ?? $user->mobile ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Member Since</label>
                            <p class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Login</label>
                            <p class="text-gray-900">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact & Address Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-card mr-2 text-blue-600"></i>
                    Contact & Address Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Mobile</label>
                        <p class="text-gray-900">{{ $user->mobile ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">City</label>
                        <p class="text-gray-900">{{ $user->city ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">State</label>
                        <p class="text-gray-900">{{ $user->state ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Pincode</label>
                        <p class="text-gray-900">{{ $user->pincode ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- This User's Sponsor ID (Their unique ID) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-id-badge mr-2 text-indigo-600"></i>
                    User's Sponsor ID
                </h3>
                <div class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-100">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2 font-semibold">Unique Sponsor ID</p>
                        @if($user->username)
                            <div class="inline-flex items-center gap-3 bg-white px-6 py-4 rounded-2xl shadow-sm">
                                <i class="fas fa-qrcode text-2xl text-indigo-600"></i>
                                <span class="text-3xl font-mono font-black text-gray-900 tracking-tight">{{ $user->username }}</span>
                                <button onclick="copySponsorId('{{ $user->username }}')" 
                                        class="ml-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-bold transition">
                                    <i class="fas fa-copy mr-1"></i> Copy
                                </button>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Use this ID for referrals
                            </p>
                        @else
                            <p class="text-gray-500">No Sponsor ID assigned</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sponsor Information (Who referred this user) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-purple-600"></i>
                    Referred By (Sponsor)
                </h3>
                @if($user->sponsor)
                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <div class="flex items-center gap-4">
                            <img src="{{ $user->sponsor->avatar_url }}" alt="{{ $user->sponsor->name }}" 
                                 class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 text-lg">{{ $user->sponsor->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $user->sponsor->email }}</p>
                                @if($user->sponsor->mobile)
                                    <p class="text-sm text-gray-600"><i class="fas fa-phone mr-1"></i> {{ $user->sponsor->mobile }}</p>
                                @endif
                                <div class="mt-2 inline-flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg">
                                    <span class="text-xs text-gray-500 font-semibold">Sponsor ID:</span>
                                    <span class="font-mono font-bold text-purple-700">{{ $user->sponsor->username }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.show', $user->sponsor) }}" 
                               class="px-5 py-2.5 bg-purple-600 text-white rounded-xl hover:bg-purple-700 text-sm font-bold transition shadow-sm">
                                <i class="fas fa-eye mr-1"></i> View Profile
                            </a>
                        </div>
                        
                        @if($user->sponsor->address || $user->sponsor->city || $user->sponsor->state)
                            <div class="mt-4 pt-4 border-t border-purple-200">
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $user->sponsor->address ?? '' }}
                                    @if($user->sponsor->city && $user->sponsor->state)
                                        , {{ $user->sponsor->city }}, {{ $user->sponsor->state }}
                                    @endif
                                    @if($user->sponsor->pincode)
                                        - {{ $user->sponsor->pincode }}
                                    @endif
                                </p>
                            </div>
                        @endif
                        
                        <div class="mt-3 pt-3 border-t border-purple-200">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Member since {{ $user->sponsor->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fas fa-user-slash text-4xl mb-2 text-gray-300"></i>
                        <p class="text-gray-500">No sponsor assigned</p>
                        <p class="text-sm text-gray-400 mt-1">This user registered without a referral</p>
                    </div>
                @endif
            </div>

            <!-- Referrals / Children -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-green-600"></i>
                    Referrals ({{ $user->children->count() }})
                </h3>
                @if($user->children->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->children as $child)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <img src="{{ $child->avatar_url }}" alt="{{ $child->name }}" 
                                     class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $child->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $child->email }}</p>
                                    <p class="text-xs text-gray-500">
                                        Joined {{ $child->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <a href="{{ route('admin.users.show', $child) }}" 
                                       class="block mt-2 text-sm text-blue-600 hover:text-blue-800">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-friends text-4xl mb-2"></i>
                        <p>No referrals yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function copySponsorId(sponsorId) {
    navigator.clipboard.writeText(sponsorId).then(() => {
        alert('Sponsor ID copied to clipboard: ' + sponsorId);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endpush
