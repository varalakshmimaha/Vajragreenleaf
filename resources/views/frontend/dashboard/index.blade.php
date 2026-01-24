@extends('layouts.frontend')

@section('title', 'User Dashboard - Vajra Green Leaf')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-12" x-data="{ activeTab: 'profile' }">
    <div class="container mx-auto px-4">
        
        <!-- Dashboard Header -->
        <div class="mb-12 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tight">User Dashboard</h1>
            <div class="w-20 h-1.5 bg-emerald-500 mx-auto mt-3 rounded-full mb-4"></div>
            <p class="text-gray-500 text-lg">Welcome back, <span class="text-emerald-600 font-bold">{{ $user->name }}</span></p>
        </div>

        <!-- Horizontal Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-10 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 max-w-2xl mx-auto">
            <button @click="activeTab = 'profile'" 
                    :class="activeTab === 'profile' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-user-circle"></i>
                Profile
            </button>
            <button @click="activeTab = 'sponsor'" 
                    :class="activeTab === 'sponsor' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-id-card"></i>
                Sponsor ID
            </button>
            <button @click="activeTab = 'password'" 
                    :class="activeTab === 'password' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-key"></i>
                Change Password
            </button>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 animate-fade-in">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tab Contents -->
        <div class="flex justify-center">
            
            <!-- Profile Tab Content -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="w-full max-w-2xl">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-xl">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Update Profile</h2>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all outline-none text-gray-900 font-medium">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all outline-none text-gray-900 font-medium">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Mobile Number</label>
                                    <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}" required pattern="[0-9]{10}"
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all outline-none text-gray-900 font-medium">
                                </div>
                                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all transform hover:scale-[1.01] active:scale-[0.99]">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sponsor ID Tab Content -->
            <div x-show="activeTab === 'sponsor'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="w-full max-w-2xl">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">My Sponsor ID</h2>
                    </div>
                    <div class="p-8 text-center sm:text-left">
                        <div class="p-8 bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-3xl border border-blue-100/50">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest block mb-4">Referral Code</span>
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <span class="text-4xl font-mono font-black text-gray-900 tracking-tighter" id="sponsor-id-val">{{ $user->username }}</span>
                                <button onclick="copySponsorId()" class="flex items-center gap-2 bg-white text-blue-600 px-6 py-3 rounded-xl font-bold border border-blue-100 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-copy"></i>
                                    Copy ID
                                </button>
                            </div>
                            <div class="mt-8 pt-8 border-t border-blue-100/50">
                                <p class="text-gray-500 text-sm leading-relaxed">
                                    <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                                    Use this Sponsor ID for referrals. This ID is unique to your account and cannot be changed.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Tab Content -->
            <div x-show="activeTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="w-full max-w-2xl">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 text-xl">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Security / Change Password</h2>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('user.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Current Password</label>
                                    <input type="password" name="current_password" required placeholder="••••••••"
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all outline-none text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">New Password</label>
                                    <input type="password" name="password" required placeholder="••••••••"
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all outline-none text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all outline-none text-gray-900">
                                </div>
                                <button type="submit" class="w-full bg-amber-500 text-white py-4 rounded-2xl font-bold hover:bg-amber-600 shadow-lg shadow-amber-100 transition-all transform hover:scale-[1.01] active:scale-[0.99]">
                                    Update Security Key
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function copySponsorId() {
        const id = document.getElementById('sponsor-id-val').textContent;
        navigator.clipboard.writeText(id).then(() => {
            alert('Sponsor ID copied to clipboard!');
        });
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
