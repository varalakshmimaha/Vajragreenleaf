@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
        
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="text-gray-600">{{ $user->name }}'s profile and referral network</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-start gap-6">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mobile</p>
                            <p class="font-medium text-gray-900">{{ $user->mobile ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Username</p>
                            <p class="font-medium text-gray-900 font-mono">{{ $user->username ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Registered</p>
                            <p class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600 mb-2">Address</p>
                        <p class="text-gray-900">
                            {{ $user->address ?? 'N/A' }}
                            @if($user->city || $user->state)
                                <br>{{ $user->city }}, {{ $user->state }} - {{ $user->pincode }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral IDs Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-semibold mb-4 opacity-90">Referral Information</h3>
            
            <div class="bg-white/20 rounded-lg p-4 mb-4 backdrop-blur-sm">
                <p class="text-sm opacity-90 mb-1">Referral ID (5-Digit)</p>
                <p class="text-3xl font-bold font-mono tracking-wider">{{ $user->referral_id ?? 'Not Assigned' }}</p>
            </div>

            @if($user->sponsor_referral_id)
                <div class="bg-white/20 rounded-lg p-4 backdrop-blur-sm">
                    <p class="text-sm opacity-90 mb-1">Sponsored By</p>
                    <p class="text-xl font-bold font-mono">{{ $user->sponsor_referral_id }}</p>
                    @if($user->sponsorByReferralId)
                        <p class="text-sm opacity-75 mt-1">{{ $user->sponsorByReferralId->name }}</p>
                    @endif
                </div>
            @else
                <div class="bg-white/10 rounded-lg p-4 text-center">
                    <p class="text-sm opacity-75">No sponsor</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Referral Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
            <p class="text-gray-600 text-sm font-medium mb-1">Total Referrals</p>
            <p class="text-3xl font-bold text-gray-900" id="totalStat">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium mb-1">Level 1</p>
            <p class="text-3xl font-bold text-gray-900" id="level1Stat">{{ $stats['level1'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium mb-1">Level 2</p>
            <p class="text-3xl font-bold text-gray-900" id="level2Stat">{{ $stats['level2'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium mb-1">Level 3</p>
            <p class="text-3xl font-bold text-gray-900" id="level3Stat">{{ $stats['level3'] }}</p>
        </div>
    </div>

    <!-- Referral Network Tree -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b bg-gradient-to-r from-emerald-50 to-green-50">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sitemap text-emerald-600"></i>
                        Referral Network Tree
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Interactive visualization of multi-level referrals</p>
                </div>
                <div class="flex gap-2">
                    <button id="searchToggle" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <button id="zoomOut" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button id="zoomReset" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <button id="zoomIn" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button id="refreshTree" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
            
            <!-- Search Bar (Hidden by default) -->
            <div id="searchBar" class="mt-4 hidden">
                <input type="text" id="treeSearch" placeholder="Search by name or referral ID..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
        
        <!-- Tree Container -->
        <div class="relative overflow-hidden bg-gray-50" style="min-height: 600px;">
            <div id="treeContainer" class="w-full h-full" style="min-height: 600px;">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center py-12">
                        <div class="animate-spin inline-block w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full mb-4"></div>
                        <p class="text-gray-600">Loading referral network...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Detail Modal -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                <h3 class="text-xl font-bold">User Details</h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .tree-node {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        min-width: 250px;
        margin: 10px;
    }
    
    .tree-node:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .tree-node.level-1 {
        border-left: 4px solid #10b981;
        background: linear-gradient(to right, #ecfdf5 0%, white 10%);
    }
    
    .tree-node.level-2 {
        border-left: 4px solid #3b82f6;
        background: linear-gradient(to right, #eff6ff 0%, white 10%);
    }
    
    .tree-node.level-3 {
        border-left: 4px solid #8b5cf6;
        background: linear-gradient(to right, #f5f3ff 0%, white 10%);
    }
    
    .tree-node.root {
        border-left: 4px solid #059669;
        background: linear-gradient(135deg, #d1fae5 0%, white 50%);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
    }
    
    .tree-node.highlighted {
        box-shadow: 0 0 0 3px #10b981;
        z-index: 10;
    }
    
    .tree-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px;
        transform-origin: center top;
    }
    
    .tree-level {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    
    .tree-branch {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .expand-btn {
        margin-top: 10px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .expand-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .collapse-btn {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }
    
    .level-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .level-badge.l1 {
        background: #10b981;
        color: white;
    }
    
    .level-badge.l2 {
        background: #3b82f6;
        color: white;
    }
    
    .level-badge.l3 {
        background: #8b5cf6;
        color: white;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    
    @media (max-width: 768px) {
        .tree-level {
            flex-direction: column;
            align-items: center;
        }
        
        .tree-node {
            min-width: 100%;
            max-width: 320px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentScale = 1;
    let currentMaxLevel = 3;
    let treeData = null;
    const userId = {{ $user->id }};

    // Load referral tree
    async function loadReferralTree(maxLevel = 3) {
        try {
            const response = await fetch(`/admin/users/${userId}/referral-tree?maxLevel=${maxLevel}`);
            treeData = await response.json();
            
            renderTree(treeData);
            updateStats(treeData);
        } catch (error) {
            console.error('Error loading referral tree:', error);
            showError();
        }
    }

    // Render tree
    function renderTree(data) {
        const container = document.getElementById('treeContainer');
        
        if (!data.referrals || data.referrals.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Referrals Yet</h3>
                    <p class="text-gray-600 mb-4">This user hasn't referred anyone yet.</p>
                    <div class="inline-block px-6 py-3 bg-emerald-500 text-white rounded-lg">
                        <i class="fas fa-share-alt mr-2"></i>
                        Share Referral Link: <span class="font-mono font-bold ml-2">${data.referralID}</span>
                    </div>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <div class="tree-container" id="treeWrapper" style="transform: scale(${currentScale});">
                ${renderRootNode(data)}
                ${renderLevel(data.referrals, 1)}
            </div>
        `;
    }

    // Render root node
    function renderRootNode(data) {
        return `
            <div class="tree-node root" onclick="showUserDetails('${data.referralID}')">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center text-white font-bold text-xl">
                        ${data.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">${data.name}</h4>
                        <p class="text-sm text-gray-600 font-mono">ID: ${data.referralID}</p>
                    </div>
                    <span class="level-badge" style="background: #059669; color: white;">ROOT</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Referrals: <strong>${data.totalDirectReferrals}</strong></span>
                </div>
            </div>
        `;
    }

    // Render level
    function renderLevel(users, level) {
        if (!users || users.length === 0) return '';
        
        const levelClass = level <= 3 ? `l${level}` : 'l3';
        
        return `
            <div class="tree-level">
                ${users.map(user => `
                    <div class="tree-branch">
                        <div class="tree-node level-${level}" onclick="showUserDetails('${user.referralID}')">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center text-white font-bold text-lg">
                                    ${user.name.charAt(0).toUpperCase()}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">${user.name}</h4>
                                    <p class="text-xs text-gray-600 font-mono">ID: ${user.referralID}</p>
                                </div>
                                <span class="level-badge ${levelClass}">L${level}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="status-badge ${user.is_active ? 'active' : 'inactive'}">
                                    <span class="w-2 h-2 rounded-full ${user.is_active ? 'bg-green-500' : 'bg-red-500'}"></span>
                                    ${user.is_active ? 'Active' : 'Inactive'}
                                </span>
                                ${user.children && user.children.length > 0 ? 
                                    `<span class="text-xs text-gray-600">${user.children.length} referral${user.children.length > 1 ? 's' : ''}</span>` : 
                                    '<span class="text-xs text-gray-400">No referrals</span>'}
                            </div>
                        </div>
                        ${user.children && user.children.length > 0 && level < currentMaxLevel ? 
                            renderLevel(user.children, level + 1) : 
                            (user.children && user.children.length > 0 && level >= currentMaxLevel ? 
                                `<button class="expand-btn" onclick="expandLevel(event, ${level + 1})">
                                    <i class="fas fa-plus-circle mr-1"></i> Load More Levels
                                </button>` : '')}
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Update stats
    function updateStats(data) {
        // Stats are already calculated server-side
    }

    // Show user details modal
    function showUserDetails(referralId) {
        const modal = document.getElementById('userModal');
        const content = document.getElementById('modalContent');
        
        content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-emerald-500"></i></div>';
        modal.style.display = 'flex';
        
        // In a real scenario, fetch user details from API
        setTimeout(() => {
            content.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center text-white font-bold text-2xl">
                            U
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">User Name</h4>
                            <p class="text-gray-600">Referral ID: ${referralId}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">user@example.com</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mobile</p>
                            <p class="font-medium">+1234567890</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Joined</p>
                            <p class="font-medium">Jan 24, 2026</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="status-badge active">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        </div>
                    </div>
                    <div class="pt-4 border-t">
                        <a href="/admin/users/${referralId}" class="block w-full text-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium">
                            View Full Profile
                        </a>
                    </div>
                </div>
            `;
        }, 500);
    }

    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    // Zoom controls
    document.getElementById('zoomIn').addEventListener('click', () => {
        currentScale = Math.min(currentScale + 0.1, 2);
        document.getElementById('treeWrapper').style.transform = `scale(${currentScale})`;
    });

    document.getElementById('zoomOut').addEventListener('click', () => {
        currentScale = Math.max(currentScale - 0.1, 0.5);
        document.getElementById('treeWrapper').style.transform = `scale(${currentScale})`;
    });

    document.getElementById('zoomReset').addEventListener('click', () => {
        currentScale = 1;
        document.getElementById('treeWrapper').style.transform = `scale(${currentScale})`;
    });

    // Search toggle
    document.getElementById('searchToggle').addEventListener('click', () => {
        const searchBar = document.getElementById('searchBar');
        searchBar.classList.toggle('hidden');
        if (!searchBar.classList.contains('hidden')) {
            document.getElementById('treeSearch').focus();
        }
    });

    // Tree search
    document.getElementById('treeSearch').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const nodes = document.querySelectorAll('.tree-node');
        
        nodes.forEach(node => {
            const text = node.textContent.toLowerCase();
            if (query && text.includes(query)) {
                node.classList.add('highlighted');
            } else {
                node.classList.remove('highlighted');
            }
        });
    });

    // Refresh tree
    document.getElementById('refreshTree').addEventListener('click', () => {
        loadReferralTree(currentMaxLevel);
    });

    // Expand level
    function expandLevel(event, level) {
        event.stopPropagation();
        currentMaxLevel = level + 2;
        loadReferralTree(currentMaxLevel);
    }

    // Show error
    function showError() {
        document.getElementById('treeContainer').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Error Loading Tree</h3>
                <p class="text-gray-600 mb-4">Failed to load referral network. Please try again.</p>
                <button onclick="loadReferralTree()" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>Retry
                </button>
            </div>
        `;
    }

    // Close modal on outside click
    document.getElementById('userModal').addEventListener('click', (e) => {
        if (e.target.id === 'userModal') {
            closeModal();
        }
    });

    // Initial load
    loadReferralTree();
</script>
@endpush
