<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Referral Network - BlueWitcher</title>
    @php
        try {
            $viteAssets = vite(['resources/css/app.css', 'resources/js/app.js'])->toHtml();
        } catch (\Throwable $e) {
            $viteAssets = null;
        }
    @endphp

    @if($viteAssets)
        {!! $viteAssets !!}
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        
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

        /* Node Style - Precise Fixed Width for Centering */
        .node-card {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            display: inline-block;
            width: 120px; /* Fixed width for consistent centering */
            box-shadow: 0 5px 15px -3px rgba(37, 99, 235, 0.3);
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid #3b82f6;
        }

        .node-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -5px rgba(37, 99, 235, 0.4);
            border-color: #60a5fa;
        }

        .node-card.root {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
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
            font-weight: 600;
            background: rgba(255, 255, 255, 0.1);
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
            padding: 50px 8px 0 8px; /* Reduced side padding */
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 100px 20px;
            color: #64748b;
            width: 100%;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 2rem;
            color: #e2e8f0;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc]">
    <div class="container mx-auto px-4 py-12 max-w-7xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">Referral Network</h1>
                <p class="text-slate-500 mt-3 text-lg font-medium">Visual hierarchical structure of your team</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-blue-50 px-6 py-4 rounded-2xl border border-blue-100">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Your Unique ID</p>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-black text-blue-700 font-mono tracking-tighter">{{ $user->referral_id }}</span>
                        <button onclick="copyReferralId('{{ $user->referral_id }}')" class="p-2 bg-white rounded-lg text-blue-400 shadow-sm hover:text-blue-600 transition-all border border-blue-100">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
                <a href="{{ route('user.dashboard') }}" class="p-4 bg-slate-900 text-white rounded-2xl hover:bg-slate-800 transition-all shadow-xl hover:-translate-y-1">
                    <i class="fa-solid fa-house"></i>
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-blue-50/50 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-users text-8xl"></i>
                </div>
                <div class="relative">
                    <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Total Network</div>
                    <div class="text-4xl font-black text-slate-900" id="totalReferrals">-</div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-emerald-50/50 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-user-check text-8xl"></i>
                </div>
                <div class="relative">
                    <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Level 1</div>
                    <div class="text-4xl font-black text-emerald-600" id="level1Count">-</div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-teal-50/50 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-network-wired text-8xl"></i>
                </div>
                <div class="relative">
                    <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Level 2</div>
                    <div class="text-4xl font-black text-teal-600" id="level2Count">-</div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-indigo-50/50 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-diagram-project text-8xl"></i>
                </div>
                <div class="relative">
                    <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Level 3+</div>
                    <div class="text-4xl font-black text-indigo-600" id="level3Count">-</div>
                </div>
            </div>
        </div>

        <!-- Tree Visualization Area -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden relative">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-200">
                        <i class="fa-solid fa-sitemap text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-none">Organization Tree</h2>
                        <p class="text-sm text-slate-500 font-bold mt-2">Scroll horizontally to view the full hierarchy</p>
                    </div>
                </div>
                <button id="refreshBtn" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-900 rounded-2xl hover:bg-slate-50 transition-all text-sm font-black shadow-sm">
                    <i class="fa-solid fa-rotate"></i>
                    Refresh Tree
                </button>
            </div>

            <div class="tree-viewport" id="viewport">
                <div class="tree-container" id="treeContainer">
                    <div class="flex flex-col items-center justify-center min-h-[400px]" id="loadingState">
                        <div class="animate-spin w-12 h-12 border-[5px] border-blue-600 border-t-transparent rounded-full mb-4"></div>
                        <p class="text-slate-400 font-bold">Connecting your network...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Detail Modal -->
    <div id="userModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md hidden items-center justify-center z-[1000] p-6">
        <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100">
            <div class="p-10 text-center">
                <div id="modalAvatar" class="w-28 h-28 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl mx-auto flex items-center justify-center text-white text-5xl font-black shadow-2xl mb-8 rotate-3">
                    ?
                </div>
                <h3 id="modalName" class="text-2xl font-black text-slate-900 mb-2 leading-tight">Name</h3>
                <p id="modalId" class="text-slate-400 font-mono font-bold mb-8 bg-slate-50 py-2 px-4 rounded-xl inline-block">ID: 00000</p>
                
                <div class="grid grid-cols-1 gap-3 mb-10">
                    <div class="bg-slate-50 p-5 rounded-2xl text-left border border-slate-100">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Network Hierarchy</div>
                        <div id="modalLevel" class="text-sm font-black text-blue-600">Level 1 Member</div>
                    </div>
                </div>

                <button onclick="closeModal()" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black hover:bg-slate-800 transition-all shadow-xl hover:-translate-y-1">
                    Close Details
                </button>
            </div>
        </div>
    </div>

    <script>
        const viewport = document.getElementById('viewport');
        const container = document.getElementById('treeContainer');

        async function loadStats() {
            try {
                const response = await fetch("{{ route('user.referrals.stats') }}");
                const data = await response.json();
                document.getElementById('totalReferrals').innerText = data.totalReferrals;
                document.getElementById('level1Count').innerText = data.level1;
                document.getElementById('level2Count').innerText = data.level2;
                document.getElementById('level3Count').innerText = data.level3;
            } catch (err) { console.error(err); }
        }

        async function loadReferralTree() {
            try {
                const response = await fetch("{{ route('user.referrals.tree') }}?maxLevel=5");
                const data = await response.json();
                renderTree(data);
            } catch (err) {
                console.error(err);
                container.innerHTML = '<div class="empty-state">Error loading network data</div>';
            }
        }

        function renderTree(data) {
            if (!data.referrals || data.referrals.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-solid fa-face-grin-stars text-blue-100"></i>
                        <h3 class="text-3xl font-black text-slate-800 mb-3">Time to Build!</h3>
                        <p class="text-slate-500 mb-8 font-medium max-w-sm text-center">Your network is ready to grow. Share ID <span class="text-blue-600 font-bold">${data.referralID}</span> and watch your team expand.</p>
                        <button onclick="copyReferralId('${data.referralID}')" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 active:scale-95">
                            Copy ID & Share Link
                        </button>
                    </div>
                `;
                return;
            }

            let treeHtml = `
                <div class="tree">
                    <div class="node-card root" onclick="showUserModal({name: '${data.name}', referralID: '${data.referralID}', level: 0})">
                        <div class="node-name">${data.name}</div>
                        <div class="node-id">${data.referralID}</div>
                        <span class="level-badge">Top</span>
                    </div>
                    ${renderLevel(data.referrals)}
                </div>
            `;
            container.innerHTML = treeHtml;
        }

        function renderLevel(members) {
            if (!members || members.length === 0) return '';
            
            let html = '<ul>';
            members.forEach(member => {
                html += `
                    <li>
                        <div class="node-card" onclick="event.stopPropagation(); showUserModal({name: '${member.name}', referralID: '${member.referralID}', level: ${member.level}})">
                            <div class="node-name">${member.name}</div>
                            <div class="node-id">${member.referralID}</div>
                            <span class="level-badge">L${member.level}</span>
                        </div>
                        ${renderLevel(member.children)}
                    </li>
                `;
            });
            html += '</ul>';
            return html;
        }

        function showUserModal(user) {
            document.getElementById('modalName').innerText = user.name;
            document.getElementById('modalId').innerText = 'ID: ' + user.referralID;
            document.getElementById('modalLevel').innerText = user.level === 0 ? 'Network Head' : 'Level ' + user.level + ' Member';
            document.getElementById('modalAvatar').innerText = user.name.charAt(0);
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('userModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userModal').classList.remove('flex');
        }

        function copyReferralId(id) {
            navigator.clipboard.writeText(id).then(() => {
                const btn = event.currentTarget;
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => btn.innerHTML = original, 2000);
            });
        }

        document.getElementById('refreshBtn').onclick = () => {
            loadStats();
            loadReferralTree();
        };

        // Initial Load
        window.addEventListener('DOMContentLoaded', () => {
            loadStats();
            loadReferralTree();
        });
    </script>
</body>
</html>
