@extends('layouts.admin')

@section('title', 'Drill-Down Explorer')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Drill-Down Explorer</h1>
            <p class="text-gray-600 mt-1">Interactive referral tree exploration</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Reports
        </a>
    </div>
</div>

<!-- Root Users Selection -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Select Root User to Explore</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($rootUsers as $root)
            <button onclick="loadUserTree({{ $root->id }})" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">{{ $root->name }}</div>
                        <div class="text-sm text-gray-600">{{ $root->referral_id }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $root->referrals_count }} direct referrals</div>
                    </div>
                </div>
            </button>
        @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                <i class="fas fa-users-slash text-4xl mb-3 opacity-20"></i>
                <p>No root users found</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Tree Container -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Referral Tree</h2>
        <p class="text-sm text-gray-600 mt-1">Click on users to expand their referrals</p>
    </div>

    <div id="treeContainer" class="p-6 min-h-[400px]">
        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
            <i class="fas fa-sitemap text-6xl mb-4 opacity-20"></i>
            <p class="text-lg font-medium">Select a root user to view their referral tree</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
let expandedNodes = new Set();

async function loadUserTree(userId) {
    const container = document.getElementById('treeContainer');
    container.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full"></div></div>';
    
    try {
        const response = await fetch(`/admin/reports/drill-down/${userId}`);
        const data = await response.json();
        
        if (data) {
            renderTree(data, container);
        }
    } catch (error) {
        container.innerHTML = '<div class="text-center py-12 text-red-600"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Error loading tree</p></div>';
    }
}

function renderTree(data, container) {
    const html = `
        <div class="flex flex-col items-center">
            <div class="mb-8">
                ${renderNode(data, true)}
            </div>
            ${data.children && data.children.length > 0 ? `
                <div class="flex gap-6 flex-wrap justify-center">
                    ${data.children.map(child => `
                        <div class="flex flex-col items-center">
                            ${renderNode(child, false)}
                            ${child.has_children ? `
                                <button onclick="toggleChildren(${child.id})" class="mt-4 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">
                                    <i class="fas fa-chevron-down mr-1"></i>
                                    ${child.direct_referrals_count} referrals
                                </button>
                            ` : ''}
                            <div id="children-${child.id}" class="mt-4 hidden"></div>
                        </div>
                    `).join('')}
                </div>
            ` : '<p class="text-gray-500 mt-4">No referrals</p>'}
        </div>
    `;
    
    container.innerHTML = html;
}

function renderNode(node, isRoot = false) {
    return `
        <div class="p-4 ${isRoot ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-green-500 to-green-600'} text-white rounded-xl shadow-lg min-w-[200px]">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-user-circle text-2xl"></i>
                <div>
                    <div class="font-bold">${node.name}</div>
                    <div class="text-xs opacity-90">${node.referral_id}</div>
                </div>
            </div>
            ${isRoot ? '<div class="text-xs bg-white bg-opacity-20 rounded px-2 py-1 inline-block">Root User</div>' : ''}
        </div>
    `;
}

async function toggleChildren(userId) {
    const childContainer = document.getElementById(`children-${userId}`);
    
    if (expandedNodes.has(userId)) {
        childContainer.classList.add('hidden');
        expandedNodes.delete(userId);
        return;
    }
    
    childContainer.innerHTML = '<div class="flex justify-center py-4"><div class="animate-spin w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div></div>';
    childContainer.classList.remove('hidden');
    
    try {
        const response = await fetch(`/admin/reports/drill-down/${userId}`);
        const data = await response.json();
        
        if (data && data.children && data.children.length > 0) {
            childContainer.innerHTML = `
                <div class="flex gap-4 flex-wrap justify-center mt-4">
                    ${data.children.map(child => `
                        <div class="flex flex-col items-center">
                            ${renderNode(child, false)}
                            ${child.has_children ? `
                                <button onclick="toggleChildren(${child.id})" class="mt-2 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">
                                    ${child.direct_referrals_count} refs
                                </button>
                            ` : ''}
                            <div id="children-${child.id}" class="hidden"></div>
                        </div>
                    `).join('')}
                </div>
            `;
            expandedNodes.add(userId);
        } else {
            childContainer.innerHTML = '<p class="text-gray-500 text-sm py-4">No referrals</p>';
        }
    } catch (error) {
        childContainer.innerHTML = '<p class="text-red-600 text-sm py-4">Error loading</p>';
    }
}
</script>
@endpush
@endsection
