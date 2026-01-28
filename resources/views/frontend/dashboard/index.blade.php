@extends('layouts.frontend')

@section('title', 'My Dashboard - Referral Program')

@push('styles')
<style>
    .tab-btn.active {
        color: #10b981;
        border-bottom-color: #10b981;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: #ffffff;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }

    .btn-primary {
        background-color: #10b981;
        color: #ffffff;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #059669;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Dashboard</h1>
                <p class="text-gray-600">Welcome, {{ $user->name }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center bg-gray-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Horizontal Tabs -->
        <nav class="flex border-b border-gray-200 mb-8 overflow-x-auto whitespace-nowrap scrollbar-hide bg-white rounded-t-xl px-2">
            <button class="tab-btn active px-6 py-4 text-sm font-semibold border-b-2 border-transparent hover:text-emerald-600 transition-all flex items-center" data-tab="profile">
                <i class="fas fa-user-circle mr-2"></i>Profile
            </button>
            <button class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent hover:text-emerald-600 transition-all flex items-center" data-tab="sponsor">
                <i class="fas fa-id-card mr-2"></i>Sponsor ID
            </button>
            <button class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent hover:text-emerald-600 transition-all flex items-center" data-tab="password">
                <i class="fas fa-key mr-2"></i>Reset Password
            </button>
            <button class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent hover:text-emerald-600 transition-all flex items-center" data-tab="referrals">
                <i class="fas fa-sitemap mr-2"></i>My Referrals
            </button>
        </nav>

        <!-- Tab Content -->
        <div class="tab-content transition-all">

            <!-- Tab 1: Profile -->
            <div class="tab-pane active" id="profile">
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Profile Settings</h3>
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" value="{{ $user->mobile }}" class="form-input bg-gray-100 cursor-not-allowed" readonly disabled>
                                <p class="text-xs text-gray-500 mt-1">Mobile number cannot be changed</p>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 2: Sponsor ID -->
            <div class="tab-pane" id="sponsor">
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl shadow-xl p-8 text-white mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-3xl font-bold mb-2">Sponsor Information</h2>
                            <p class="text-emerald-50">Your unique identity in our network</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/30">
                            <p class="text-sm opacity-90 mb-1 font-medium">Your Referral / Sponsor ID</p>
                            <p class="text-4xl font-bold font-mono tracking-wider">{{ $user->referral_id }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 min-w-[200px] flex-shrink-0">
                        <p class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Total Referrals</p>
                        <p class="text-4xl font-black text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 min-w-[200px] flex-shrink-0">
                        <p class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Level 1</p>
                        <p class="text-4xl font-black text-gray-900">{{ $stats['level1'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 min-w-[200px] flex-shrink-0">
                        <p class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Level 2</p>
                        <p class="text-4xl font-black text-gray-900">{{ $stats['level2'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 min-w-[200px] flex-shrink-0">
                        <p class="text-gray-500 text-sm font-semibold mb-1 uppercase tracking-wider">Level 3</p>
                        <p class="text-4xl font-black text-gray-900">{{ $stats['level3'] }}</p>
                    </div>
                </div>

                <div class="mt-8 bg-white rounded-xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Invite Others</h3>
                    <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100">
                        <label class="block text-sm font-semibold text-emerald-800 mb-2">Your Referral Link</label>
                        <div class="flex flex-col md:flex-row gap-3">
                            <input type="text" id="referralLinkInput" readonly value="{{ $referralLink }}"
                                class="flex-1 bg-white border border-emerald-200 rounded-lg px-4 py-3 text-sm font-mono text-emerald-700 focus:outline-none">
                            <div class="flex gap-2">
                                <button onclick="copyReferralLink()" class="flex-1 md:flex-initial bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-copy"></i> <span>Copy</span>
                                </button>
                                <button onclick="shareWhatsApp()" class="flex-1 md:flex-initial bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp"></i> <span>WhatsApp</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Reset Password -->
            <div class="tab-pane" id="password">
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Security & Password</h3>
                    <p class="text-gray-600 mb-8">Ensure your account is using a strong, unique password to stay secure.</p>

                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="password" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-input" required>
                            </div>
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="w-full btn-primary">
                                <i class="fas fa-shield-alt mr-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 4: My Referrals -->
            <div class="tab-pane" id="referrals">
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">My Referral Network</h3>
                            <p class="text-gray-500">Interactive overview of your growing team</p>
                        </div>
                        <a href="{{ route('user.referrals.dashboard') }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg transition-all flex items-center">
                            <i class="fas fa-sitemap mr-2"></i>Full Interactive Tree
                        </a>
                    </div>

                    @if($stats['total'] > 0)
                        <div class="py-8">
                            <!-- Simplified Tree Preview -->
                            <div class="flex justify-center mb-12">
                                <div class="bg-gradient-to-br from-emerald-500 to-green-700 rounded-2xl p-6 text-white shadow-xl text-center border-4 border-white/20 min-w-[180px]">
                                    <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 flex items-center justify-center shadow-inner">
                                        <span class="text-3xl font-black text-emerald-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <h4 class="font-bold text-lg leading-tight">{{ $user->name }}</h4>
                                    <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase">Root Member</span>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide px-2">
                                    @foreach($user->referrals()->take(5)->get() as $level1)
                                        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:border-emerald-300 transition-all group relative min-w-[280px] flex-shrink-0">
                                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-emerald-500 text-white px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest leading-none">Level 1</div>
                                            <div class="flex items-center gap-4 mb-4">
                                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                                    <span class="font-black text-emerald-700 group-hover:text-white">{{ strtoupper(substr($level1->name, 0, 1)) }}</span>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h5 class="font-bold text-gray-900 truncate">{{ $level1->name }}</h5>
                                                    <p class="text-xs text-emerald-600 font-semibold">ID: {{ $level1->referral_id }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50 text-xs">
                                                <span class="flex items-center px-2 py-1 {{ $level1->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} rounded-lg font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $level1->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-1.5 animate-pulse"></span>
                                                    {{ $level1->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-lg">
                                                    <i class="fas fa-users mr-1"></i>{{ $level1->referrals()->count() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($stats['level1'] > 3)
                                    <div class="text-center mt-12">
                                        <a href="{{ route('user.referrals.dashboard') }}" class="inline-flex items-center px-8 py-3 bg-gray-100 hover:bg-emerald-500 hover:text-white text-gray-700 rounded-full font-black transition-all shadow-sm">
                                            Discover All {{ $stats['level1'] }} Members <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-4xl text-gray-300"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Referrals Yet</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">Start sharing your Referral ID from the "Sponsor ID" tab to build your network.</p>
                        </div>
                    @endif

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mt-12 rounded-r-xl">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                            <div class="ml-4">
                                <h4 class="text-blue-900 font-bold mb-1">Growth Mechanism</h4>
                                <p class="text-blue-800 text-sm leading-relaxed">
                                    Your team's expansion follows a multi-level structure. Direct referrals form your <strong>Level 1</strong>, and as they invite others, your <strong>Level 2</strong> and <strong>Level 3</strong> networks materialize.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const panes = document.querySelectorAll('.tab-pane');

        // Handle browser hash
        const hash = window.location.hash.substring(1);
        if (hash) {
            const targetTab = document.querySelector(`[data-tab="${hash}"]`);
            if (targetTab) {
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('active'));
                targetTab.classList.add('active');
                document.getElementById(hash).classList.add('active');
            }
        }

        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabId = btn.getAttribute('data-tab');

                // Update UI
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('active'));

                btn.classList.add('active');
                document.getElementById(tabId).classList.add('active');

                // Update hash without jump
                history.pushState(null, null, `#${tabId}`);
            });
        });
    });

    function copyReferralLink() {
        const copyText = document.getElementById("referralLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value).then(() => {
            alert("Referral link copied to clipboard!");
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    function shareWhatsApp() {
        const referralLink = document.getElementById("referralLinkInput").value;
        const text = encodeURIComponent("Join me on this platform! Click here to register: " + referralLink);
        window.open("https://wa.me/?text=" + text, "_blank");
    }
</script>
@endpush
