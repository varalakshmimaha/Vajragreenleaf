@extends('layouts.admin')

@section('title', 'Customer Details - ' . $customer->name)

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 transition-colors mb-4 font-bold text-sm uppercase tracking-wider">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Customers
                </a>
                <h1 class="text-4xl font-black text-slate-900 leading-tight">Customer Profile</h1>
                <p class="text-slate-500 font-medium text-lg">View account details and referral network</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-bold shadow-lg">
                    <i class="fas fa-edit mr-2"></i>Edit Customer
                </a>
            </div>
        </div>
    </div>

    <!-- Customer Profile & Referral Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="relative">
                    <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="w-32 h-32 rounded-3xl object-cover shadow-2xl ring-4 ring-slate-50">
                    <span class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center {{ $customer->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-6">
                        <h2 class="text-3xl font-black text-slate-900">{{ $customer->name }}</h2>
                        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest {{ $customer->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $customer->is_active ? 'Active Account' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Mobile Number</p>
                            <p class="font-bold text-slate-700">{{ $customer->mobile ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Sponsor Id</p>
                            <p class="font-bold text-slate-700 font-mono">{{ $customer->referral_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Member Since</p>
                            <p class="font-bold text-slate-700">{{ $customer->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Last Login</p>
                            <p class="font-bold text-slate-700">{{ $customer->last_login_at ? $customer->last_login_at->format('M d, Y h:i A') : 'Never' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Network Card -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
            <i class="fas fa-users-rays absolute -bottom-10 -right-10 text-9xl text-white/10 rotate-12"></i>
            <h3 class="text-xl font-bold mb-8 uppercase tracking-widest opacity-80">Network Identity</h3>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 mb-6 border border-white/20">
                <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-70 text-emerald-100">Sponsor Id</p>
                <p class="text-4xl font-black font-mono tracking-tighter">{{ $customer->referral_id ?? 'PENDING' }}</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <p class="text-xs font-bold uppercase tracking-widest mb-2 opacity-70 text-emerald-100">Sponsor By</p>
                @if($customer->sponsor_referral_id)
                    <p class="text-2xl font-black font-mono mb-1">{{ $customer->sponsor_referral_id }}</p>
                    @if($customer->sponsorByReferralId)
                        <p class="text-sm font-bold opacity-80 flex items-center gap-2">
                             <i class="fas fa-user-check text-emerald-300 text-xs"></i>
                             {{ $customer->sponsorByReferralId->name }}
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
            <p class="text-4xl font-black text-emerald-600">{{ $stats['level1'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Level 2</p>
            <p class="text-4xl font-black text-teal-600">{{ $stats['level2'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Level 3</p>
            <p class="text-4xl font-black text-cyan-600">{{ $stats['level3'] }}</p>
        </div>
    </div>

    <!-- Interactive Network Tree -->
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden relative mb-20">
        <div class="p-8 border-b bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-emerald-600 rounded-[1.25rem] flex items-center justify-center text-white shadow-xl shadow-emerald-200">
                    <i class="fas fa-sitemap text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 leading-tight">Network Visualization</h2>
                    <p class="text-slate-500 font-bold text-sm">Interactive hierarchical tree for {{ $customer->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button id="refreshTree" class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all font-bold shadow-lg shadow-emerald-100">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Tree
                </button>
            </div>
        </div>

        <div class="tree-viewport" id="treeViewport">
            <div class="tree-container" id="treeContainer">
                <div class="flex items-center justify-center min-h-[500px]">
                    <div class="animate-spin w-12 h-12 border-[5px] border-emerald-600 border-t-transparent rounded-full shadow-inner"></div>
                </div>
            </div>

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
@endsection

@push('styles')
<style>
    .tree-viewport {
        width: 100%;
        height: 700px;
        overflow: auto;
        position: relative;
        background: #f8fafc;
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .tree-container {
        display: inline-block;
        padding: 60px;
        min-width: 100%;
        min-height: 100%;
        transform-origin: top left;
        transition: transform 0.3s ease;
    }

    .tree { display: inline-flex; flex-direction: column; align-items: center; min-width: max-content; }
    .tree ul { padding-top: 50px; position: relative; display: flex; justify-content: center; align-items: flex-start; margin: 0; padding-left: 0; gap: 20px; }
    .tree li { text-align: center; list-style-type: none; position: relative; padding: 50px 8px 0 8px; display: flex; flex-direction: column; align-items: center; }
    .tree li::before, .tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #cbd5e1; width: 50%; height: 50px; z-index: 1; }
    .tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
    .tree li:only-child::after, .tree li:only-child::before { display: none; }
    .tree li:only-child { padding-top: 0; }
    .tree li:first-child::before, .tree li:last-child::after { border: 0 none; }
    .tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 12px 0 0; }
    .tree li:first-child::after { border-radius: 12px 0 0 0; }
    .tree ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #cbd5e1; width: 0; height: 50px; margin-left: -1px; z-index: 1; }

    .zoom-controls { position: absolute; bottom: 20px; right: 20px; display: flex; gap: 8px; z-index: 100; background: white; padding: 8px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
    .zoom-btn { width: 36px; height: 36px; border: none; background: #f8fafc; color: #475569; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; transition: all 0.2s; }
    .zoom-btn:hover { background: #e2e8f0; color: #1e293b; }

    .node-card { background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 6px 12px; border-radius: 8px; display: inline-block; min-width: 110px; max-width: 130px; box-shadow: 0 5px 10px -2px rgba(5, 150, 105, 0.3); position: relative; z-index: 10; transition: all 0.3s ease; cursor: pointer; border: 1.5px solid #10b981; }
    .node-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -5px rgba(5, 150, 105, 0.4); }
    .node-card.root { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border: 2px solid #10b981; min-width: 120px; padding: 8px 16px; }
    .node-name { font-weight: 800; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px; }
    .node-id { font-size: 0.65rem; opacity: 0.9; font-family: monospace; margin-top: 1px; font-weight: 700; background: rgba(255, 255, 255, 0.15); padding: 0px 5px; border-radius: 4px; display: inline-block; }
    .level-badge { position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 900; padding: 1px 6px; border-radius: 4px; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3); z-index: 20; text-transform: uppercase; }
</style>
@endpush

@push('scripts')
<script>
    let currentScale = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    const viewport = document.getElementById('treeViewport');
    const container = document.getElementById('treeContainer');
    const targetCustomerId = {{ $customer->id }};

    document.addEventListener('DOMContentLoaded', () => {
        loadNetworkTree();
        setupInteraction();
    });

    async function loadNetworkTree() {
        try {
            const response = await fetch(`/admin/customers/${targetCustomerId}/referral-tree?maxLevel=5`);
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
                    <p class="mt-2 font-medium">This customer hasn't referred anyone yet.</p>
                </div>
            `;
            return;
        }

        const networkHtml = `
            <div class="tree">
                <div class="node-card root">
                    <div class="node-name">${data.name}</div>
                    <div class="node-id">${data.referralID}</div>
                    <span class="level-badge">Root</span>
                </div>
                ${generateTreeLevels(data.referrals)}
            </div>
        `;
        container.innerHTML = networkHtml;
        setTimeout(resetZoomState, 150);
    }

    function generateTreeLevels(nodes, currentDepth = 1, maxDepth = 2) {
        if (!nodes || nodes.length === 0) return '';
        let html = '<ul>';
        nodes.forEach((node) => {
            const hasChildren = node.children && node.children.length > 0;
            const shouldShowChildren = currentDepth < maxDepth;
            html += `
                <li>
                    <div class="node-card">
                        <div class="node-name">${node.name}</div>
                        <div class="node-id">${node.referralID}</div>
                        <span class="level-badge">L${node.level}</span>
                    </div>
                    ${shouldShowChildren ? generateTreeLevels(node.children, currentDepth + 1, maxDepth) : ''}
                </li>
            `;
        });
        html += '</ul>';
        return html;
    }

    function setupInteraction() {
        viewport.onmousedown = (e) => { isDragging = true; startX = e.pageX - viewport.offsetLeft; startY = e.pageY - viewport.offsetTop; scrollLeft = viewport.scrollLeft; scrollTop = viewport.scrollTop; };
        window.onmouseup = () => isDragging = false;
        viewport.onmouseleave = () => isDragging = false;
        viewport.onmousemove = (e) => { if (!isDragging) return; e.preventDefault(); viewport.scrollLeft = scrollLeft - (e.pageX - viewport.offsetLeft - startX); viewport.scrollTop = scrollTop - (e.pageY - viewport.offsetTop - startY); };
        viewport.onwheel = (e) => { e.preventDefault(); applyZoom(e.deltaY > 0 ? -0.1 : 0.1); };
        document.getElementById('zoomIn').onclick = () => applyZoom(0.2);
        document.getElementById('zoomOut').onclick = () => applyZoom(-0.2);
        document.getElementById('zoomReset').onclick = resetZoom;
        document.getElementById('refreshTree').onclick = loadNetworkTree;
    }

    function applyZoom(delta) { currentScale = Math.min(Math.max(0.5, currentScale + delta), 1.5); container.style.transform = `scale(${currentScale})`; }
    function resetZoom() { currentScale = 1; container.style.transform = 'scale(1)'; setTimeout(() => { const fullWidth = container.scrollWidth; const viewWidth = viewport.clientWidth; if (fullWidth > viewWidth) { viewport.scrollLeft = (fullWidth - viewWidth) / 2; } }, 100); }
    function resetZoomState() { const fullWidth = container.scrollWidth; const viewWidth = viewport.clientWidth; if (fullWidth > viewWidth) { viewport.scrollLeft = (fullWidth - viewWidth) / 2; } }
</script>
@endpush
