@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-colors mb-4 font-bold text-sm uppercase tracking-wider">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
                <h1 class="text-4xl font-black text-slate-900 leading-tight">User Profile</h1>
                <p class="text-slate-500 font-medium text-lg">Manage account details and view network hierarchy</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-bold shadow-lg">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile & Referral Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-3xl object-cover shadow-2xl ring-4 ring-slate-50">
                    <span class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-6">
                        <h2 class="text-3xl font-black text-slate-900">{{ $user->name }}</h2>
                        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Active Account' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Email Address</p>
                            <p class="font-bold text-slate-700">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Phone Number</p>
                            <p class="font-bold text-slate-700">{{ $user->mobile ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Username / ID</p>
                            <p class="font-bold text-slate-700 font-mono">{{ $user->username ?? $user->referral_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Member Since</p>
                            <p class="font-bold text-slate-700">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Residential Address</p>
                <p class="text-slate-700 font-medium leading-relaxed">
                    {{ $user->address ?? 'No address details provided.' }}
                    @if($user->city || $user->state)
                        <br><span class="text-slate-900 font-bold">{{ $user->city }}{{ $user->city && $user->state ? ', ' : '' }}{{$user->state}}</span> 
                        @if($user->pincode) <span class="text-slate-400 font-mono px-2">|</span> {{ $user->pincode }} @endif
                    @endif
                </p>
            </div>
        </div>

        <!-- Referral Network Card -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
            <i class="fas fa-users-rays absolute -bottom-10 -right-10 text-9xl text-white/10 rotate-12"></i>
            <h3 class="text-xl font-bold mb-8 uppercase tracking-widest opacity-80">Network Identity</h3>
            
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 mb-6 border border-white/20">
                <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-70 text-blue-100">Your Referral ID</p>
                <p class="text-4xl font-black font-mono tracking-tighter">{{ $user->referral_id ?? 'PENDING' }}</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-70 text-blue-100">Sponsor Details</p>
                @if($user->sponsor_referral_id)
                    <p class="text-2xl font-black font-mono mb-1">{{ $user->sponsor_referral_id }}</p>
                    @if($user->sponsorByReferralId)
                        <p class="text-sm font-bold opacity-80 flex items-center gap-2">
                             <i class="fas fa-user-check text-emerald-400 text-xs"></i>
                             {{ $user->sponsorByReferralId->name }}
                        </p>
                    @endif
                @else
                    <p class="text-lg font-bold opacity-60 italic">No Direct Sponsor</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Referral Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Total Network</p>
            <p class="text-4xl font-black text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Level 1 (Direct)</p>
            <p class="text-4xl font-black text-blue-600">{{ $stats['level1'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Level 2</p>
            <p class="text-4xl font-black text-teal-600">{{ $stats['level2'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Level 3</p>
            <p class="text-4xl font-black text-indigo-600">{{ $stats['level3'] }}</p>
        </div>
    </div>

    <!-- Interactive Network Tree -->
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden relative mb-20">
        <div class="p-8 border-b bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-600 rounded-[1.25rem] flex items-center justify-center text-white shadow-xl shadow-blue-200">
                    <i class="fas fa-sitemap text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 leading-tight">Network Visualization</h2>
                    <p class="text-slate-500 font-bold text-sm">Interactive hierarchical tree for {{ $user->name }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button id="refreshTree" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-all font-bold shadow-lg shadow-blue-100">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Tree
                </button>
            </div>
        </div>

        <div class="tree-viewport" id="treeViewport">
            <div class="tree-container" id="treeContainer">
                <!-- Data will be loaded via JS -->
                <div class="flex items-center justify-center min-h-[500px]">
                    <div class="animate-spin w-12 h-12 border-[5px] border-blue-600 border-t-transparent rounded-full shadow-inner"></div>
                </div>
            </div>
            
            <!-- Zoom Controls -->
            <div class="zoom-controls">
                <button class="zoom-btn" id="zoomOut" title="Zoom Out">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="zoom-btn" id="zoomReset" title="Reset Zoom">
                    <i class="fas fa-expand"></i>
                </button>
                <button class="zoom-btn" id="zoomIn" title="Zoom In">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="userModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md hidden items-center justify-center z-[2000] p-6">
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full overflow-hidden transform transition-all border border-slate-100">
            <div class="p-10 text-center">
                <div id="modalAvatar" class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-[2rem] mx-auto flex items-center justify-center text-white text-4xl font-black shadow-2xl mb-8 rotate-3">
                    ?
                </div>
                <h3 id="modalName" class="text-2xl font-black text-slate-900 mb-2 leading-tight">User Details</h3>
                <p id="modalId" class="text-slate-400 font-mono font-bold mb-8 text-sm">ID: 00000</p>
                
                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-slate-50 p-5 rounded-2xl text-left border border-slate-100">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Network Level</div>
                        <div id="modalLevel" class="text-sm font-black text-blue-600">Level 1</div>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-2xl text-left border border-slate-100">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Direct Referrals</div>
                        <div id="modalChildren" class="text-sm font-black text-slate-800">0 People</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a id="viewProfileLink" href="#" class="block w-full py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-slate-800 transition-all shadow-xl hover:-translate-y-1">
                        View Full Profile
                    </a>
                    <button onclick="closeModal()" class="w-full py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all">
                        Close Modal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Tree Visualization - Zoom-Safe Scrollable Container */
    .tree-viewport {
        width: 100%;
        height: 700px;
        overflow: auto;
        position: relative;
        background: #f8fafc;
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 2px 10px 0 rgba(0, 0, 0, 0.02);
    }
    
    .tree-container {
        display: inline-block;
        padding: 60px;
        min-width: 100%;
        min-height: 100%;
        transform-origin: top left;
        transition: transform 0.3s ease;
    }

    .tree {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        min-width: max-content;
    }

    .tree ul {
        padding-top: 50px; 
        position: relative;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin: 0;
        padding-left: 0;
        gap: 20px;
    }

    .tree li {
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 50px 15px 0 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Horizontal connection lines */
    .tree li::before, .tree li::after {
        content: '';
        position: absolute; 
        top: 0; 
        right: 50%;
        border-top: 2px solid #cbd5e1;
        width: 50%; 
        height: 50px;
        z-index: 1;
    }
    .tree li::after {
        right: auto; 
        left: 50%;
        border-left: 2px solid #cbd5e1;
    }

    /* Remove lines for single children */
    .tree li:only-child::after, .tree li:only-child::before {
        display: none;
    }
    .tree li:only-child { padding-top: 0; }
    
    /* Remove connectors from the edges */
    .tree li:first-child::before, .tree li:last-child::after {
        border: 0 none;
    }
    .tree li:last-child::before {
        border-right: 2px solid #cbd5e1;
        border-radius: 0 12px 0 0;
    }
    .tree li:first-child::after {
        border-radius: 12px 0 0 0;
    }

    /* Parent vertical downward line */
    .tree ul::before {
        content: '';
        position: absolute; 
        top: 0; 
        left: 50%;
        border-left: 2px solid #cbd5e1;
        width: 0; 
        height: 50px;
        margin-left: -1px;
        z-index: 1;
    }

    /* Zoom Controls */
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 8px;
        z-index: 100;
        background: white;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .zoom-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f8fafc;
        color: #475569;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s;
    }

    .zoom-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .zoom-btn:active {
        transform: scale(0.95);
    }

    /* Node Style - Precise Compact Design */
    .node-card {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
        min-width: 110px;
        max-width: 130px;
        box-shadow: 0 5px 10px -2px rgba(37, 99, 235, 0.3);
        position: relative;
        z-index: 10;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1.5px solid #3b82f6;
    }

    .node-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -5px rgba(37, 99, 235, 0.4);
        border-color: #60a5fa;
    }

    .node-card.root {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border: 2px solid #60a5fa;
        min-width: 120px;
        padding: 8px 16px;
    }

    .node-name {
        font-weight: 800;
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
        letter-spacing: -0.01em;
    }

    .node-id {
        font-size: 0.65rem;
        opacity: 0.9;
        font-family: 'JetBrains Mono', monospace;
        margin-top: 1px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.15);
        padding: 0px 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .level-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        font-size: 0.6rem;
        font-weight: 900;
        padding: 1px 6px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        z-index: 20;
        text-transform: uppercase;
    }
    
    .tree li {
        padding: 50px 8px 0 8px; /* Reduced side padding from 15px to 8px */
    }
    
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentScale = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    const viewport = document.getElementById('treeViewport');
    const container = document.getElementById('treeContainer');
    const targetUserId = {{ $user->id }};

    // Initialization
    document.addEventListener('DOMContentLoaded', () => {
        loadNetworkTree();
        setupInteraction();
    });

    async function loadNetworkTree() {
        try {
            const response = await fetch(`/admin/users/${targetUserId}/referral-tree?maxLevel=5`);
            const data = await response.json();
            renderNetwork(data);
        } catch (err) {
            console.error('Tree Load Fail:', err);
            container.innerHTML = '<div class="p-20 text-slate-400 font-bold">Failed to load network visualization</div>';
        }
    }

    function renderNetwork(data) {
        if (!data.referrals || data.referrals.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-24 text-slate-400">
                    <i class="fas fa-users-slash text-6xl mb-6 opacity-20"></i>
                    <p class="text-xl font-black text-slate-800">No Referrals Recorded</p>
                    <p class="mt-2 font-medium">This user hasn't referred anyone to the platform yet.</p>
                </div>
            `;
            return;
        }

        const networkHtml = `
            <div class="tree">
                <div class="node-card root" onclick="showUserPreview(${JSON.stringify({name: data.name, referralID: data.referralID, level: 0, children: data.referrals.length})})">
                    <div class="node-name">${data.name}</div>
                    <div class="node-id">${data.referralID}</div>
                    <span class="level-badge">Root</span>
                </div>
                ${generateTreeLevels(data.referrals)}
            </div>
        `;
        container.innerHTML = networkHtml;
        
        // Auto-center after render
        setTimeout(resetZoomState, 150);
    }

    function generateTreeLevels(nodes, currentDepth = 1, maxDepth = 2) {
        if (!nodes || nodes.length === 0) return '';
        
        let html = '<ul>';
        nodes.forEach((node, index) => {
            const nodeData = {
                name: node.name,
                referralID: node.referralID,
                level: node.level,
                children: node.children ? node.children.length : 0
            };
            
            const hasChildren = node.children && node.children.length > 0;
            const shouldShowChildren = currentDepth < maxDepth;
            const shouldShowLoadMore = currentDepth === maxDepth && hasChildren;
            
            // Create unique ID for this button
            const buttonId = `load-btn-${node.referralID}-${currentDepth}`;
            
            // Store children data in a global object for later retrieval
            if (shouldShowLoadMore) {
                const childrenKey = `children_${node.referralID}_${currentDepth}`;
                window.treeChildrenData = window.treeChildrenData || {};
                window.treeChildrenData[childrenKey] = node.children;
            }
            
            html += `
                <li>
                    <div class="node-card" onclick="event.stopPropagation(); showUserPreview(${JSON.stringify(nodeData)})">
                        <div class="node-name">${node.name}</div>
                        <div class="node-id">${node.referralID}</div>
                        <span class="level-badge">L${node.level}</span>
                    </div>
                    ${shouldShowChildren ? generateTreeLevels(node.children, currentDepth + 1, maxDepth) : ''}
                    ${shouldShowLoadMore ? `
                        <div class="load-more-container" style="margin-top: 15px; text-align: center;">
                            <button id="${buttonId}" 
                                    onclick="event.stopPropagation(); loadMoreLevels('${buttonId}', '${node.referralID}', ${currentDepth + 1}, ${node.level + 1}, ${node.children.length})" 
                                    class="load-more-btn" 
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 24px; border-radius: 20px; border: none; cursor: pointer; font-weight: 600; font-size: 13px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease; text-transform: none;">
                                <i class="fas fa-chevron-down" style="margin-right: 8px;"></i>
                                Load More
                            </button>
                        </div>
                    ` : ''}
                </li>
            `;
        });
        html += '</ul>';
        return html;
    }

    function loadMoreLevels(buttonId, referralId, currentDepth, nextLevel, childCount) {
        const childrenKey = `children_${referralId}_${currentDepth - 1}`;
        const children = window.treeChildrenData[childrenKey];
        
        if (!children) {
            console.error('Children data not found for', childrenKey);
            return;
        }
        
        // Find the specific button that was clicked using its unique ID
        const clickedButton = document.getElementById(buttonId);
        
        if (!clickedButton) {
            console.error('Button not found:', buttonId);
            return;
        }
        
        const container = clickedButton.closest('.load-more-container');
        const parentLi = container.closest('li');
        
        // Show loading state
        clickedButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Loading...';
        clickedButton.disabled = true;
        
        setTimeout(() => {
            // Generate the next level
            const newLevelsHtml = generateTreeLevels(children, currentDepth, currentDepth);
            
            // Insert the new levels before the load more button
            container.insertAdjacentHTML('beforebegin', newLevelsHtml);
            
            // Remove the load more button with fade out
            container.style.transition = 'opacity 0.3s ease';
            container.style.opacity = '0';
            setTimeout(() => {
                container.remove();
            }, 300);
            
            // Add fade-in animation to new nodes
            const newUl = parentLi.querySelector('ul:last-of-type');
            if (newUl) {
                newUl.style.opacity = '0';
                newUl.style.transform = 'translateY(-10px)';
                newUl.style.transition = 'all 0.4s ease';
                
                setTimeout(() => {
                    newUl.style.opacity = '1';
                    newUl.style.transform = 'translateY(0)';
                }, 50);
            }
        }, 300);
    }

    // Interactive Controls
    function setupInteraction() {
        viewport.onmousedown = (e) => {
            isDragging = true;
            startX = e.pageX - viewport.offsetLeft;
            startY = e.pageY - viewport.offsetTop;
            scrollLeft = viewport.scrollLeft;
            scrollTop = viewport.scrollTop;
        };

        window.onmouseup = () => isDragging = false;
        viewport.onmouseleave = () => isDragging = false;

        viewport.onmousemove = (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - viewport.offsetLeft;
            const y = e.pageY - viewport.offsetTop;
            viewport.scrollLeft = scrollLeft - (x - startX);
            viewport.scrollTop = scrollTop - (y - startY);
        };

        // Scroll to zoom with mouse wheel
        viewport.onwheel = (e) => {
            e.preventDefault();
            applyZoom(e.deltaY > 0 ? -0.1 : 0.1);
        };

        // Zoom button controls
        document.getElementById('zoomIn').onclick = () => applyZoom(0.2);
        document.getElementById('zoomOut').onclick = () => applyZoom(-0.2);
        document.getElementById('zoomReset').onclick = resetZoom;
        document.getElementById('refreshTree').onclick = loadNetworkTree;
    }

    function applyZoom(delta) {
        currentScale = Math.min(Math.max(0.5, currentScale + delta), 1.5);
        container.style.transform = `scale(${currentScale})`;
    }

    function resetZoom() {
        currentScale = 1;
        container.style.transform = 'scale(1)';
        
        // Center the tree horizontally
        setTimeout(() => {
            const fullWidth = container.scrollWidth;
            const viewWidth = viewport.clientWidth;
            if (fullWidth > viewWidth) {
                viewport.scrollLeft = (fullWidth - viewWidth) / 2;
            }
        }, 100);
    }

    function resetZoomState() {
        // Horizontally center after initial load
        const fullWidth = container.scrollWidth;
        const viewWidth = viewport.clientWidth;
        if (fullWidth > viewWidth) {
            viewport.scrollLeft = (fullWidth - viewWidth) / 2;
        }
    }

    // Modal Operations
    function showUserPreview(data) {
        document.getElementById('modalName').innerText = data.name;
        document.getElementById('modalId').innerText = 'ID: ' + data.referralID;
        document.getElementById('modalLevel').innerText = 'Network Level ' + data.level;
        document.getElementById('modalChildren').innerText = data.children + ' Direct Referrals';
        document.getElementById('modalAvatar').innerText = data.name.charAt(0);
        document.getElementById('viewProfileLink').href = `/admin/users?search=${data.referralID}`; // Search for user in list
        
        const modal = document.getElementById('userModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('userModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush
