<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Referrals - Multilevel Network</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .referral-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border-left: 4px solid #10b981;
        }
        .level-badge-1 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .level-badge-2 { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .level-badge-3 { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .tree-line {
            position: relative;
        }
        .tree-line::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
    </style>
</head>
<body class="bg-[#f0fdf4] min-h-screen">
    
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Referral Network</h1>
                    <p class="text-gray-600">Track and manage your multilevel referral tree</p>
                </div>
                <div class="text-right">
                    <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg">
                        <p class="text-sm opacity-90">Your Referral ID</p>
                        <p class="text-3xl font-bold">{{ $user->referral_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="statsCards">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Total Referrals</p>
                <p class="text-3xl font-bold text-gray-900" id="totalReferrals">-</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Level 1</p>
                <p class="text-3xl font-bold text-gray-900" id="level1Count">-</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Level 2</p>
                <p class="text-3xl font-bold text-gray-900" id="level2Count">-</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Level 3</p>
                <p class="text-3xl font-bold text-gray-900" id="level3Count">-</p>
            </div>
        </div>

        <!-- Referral Tree -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Referral Tree</h2>
                <div class="flex gap-3">
                    <button id="refreshBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        🔄 Refresh
                    </button>
                    <button id="loadMoreBtn" class="hidden px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors">
                        Load All Levels
                    </button>
                </div>
            </div>
            
            <div id="referralTree" class="space-y-4">
                <div class="text-center py-12 text-gray-500">
                    <div class="animate-spin inline-block w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full mb-4"></div>
                    <p>Loading referral tree...</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <script>
        let currentMaxLevel = 3;
        let hasMoreLevels = false;

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch("{{ route('user.referrals.stats') }}");
                const data = await response.json();
                
                document.getElementById('totalReferrals').textContent = data.totalReferrals;
                document.getElementById('level1Count').textContent = data.level1;
                document.getElementById('level2Count').textContent = data.level2;
                document.getElementById('level3Count').textContent = data.level3;
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load referral tree
        async function loadReferralTree(maxLevel = 3) {
            try {
                const response = await fetch(`{{ route('user.referrals.tree') }}?maxLevel=${maxLevel}`);
                const data = await response.json();
                
                hasMoreLevels = data.hasMore;
                currentMaxLevel = maxLevel;
                
                const treeDiv = document.getElementById('referralTree');
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                
                if (data.referrals.length === 0) {
                    treeDiv.innerHTML = `
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">👥</div>
                            <p class="text-xl font-semibold text-gray-900 mb-2">No Referrals Yet</p>
                            <p class="text-gray-600 mb-6">Share your referral ID <strong>${data.referralID}</strong> to start building your network</p>
                            <button onclick="copyReferralId('${data.referralID}')" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors">
                                📋 Copy Referral ID
                            </button>
                        </div>
                    `;
                    loadMoreBtn.classList.add('hidden');
                } else {
                    treeDiv.innerHTML = renderReferralTree(data.referrals);
                    loadMoreBtn.classList.toggle('hidden', !hasMoreLevels);
                }
            } catch (error) {
                console.error('Error loading referral tree:', error);
                document.getElementById('referralTree').innerHTML = `
                    <div class="text-center py-12 text-red-600">
                        <p class="text-xl">Error loading referral tree</p>
                        <p class="text-sm mt-2">${error.message}</p>
                    </div>
                `;
            }
        }

        // Render referral tree HTML
        function renderReferralTree(referrals, level = 1) {
            if (!referrals || referrals.length === 0) return '';
            
            const levelColors = {
                1: 'border-emerald-500 bg-emerald-50',
                2: 'border-blue-500 bg-blue-50',
                3: 'border-purple-500 bg-purple-50'
            };
            
            const levelBadges = {
                1: 'level-badge-1',
                2: 'level-badge-2',
                3: 'level-badge-3'
            };
            
            let html = '';
            referrals.forEach((referral, index) => {
                const borderColor = levelColors[level] || 'border-gray-500 bg-gray-50';
                const badgeClass = levelBadges[level] || 'bg-gray-500';
                const indent = (level - 1) * 40;
                
                html += `
                    <div class="relative" style="margin-left: ${indent}px;">
                        <div class="referral-card rounded-lg p-4 border-l-4 ${borderColor} hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                                        ${referral.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">${referral.name}</h3>
                                        <p class="text-sm text-gray-600">ID: <span class="font-mono font-bold">${referral.referralID}</span></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="${badgeClass} text-white px-3 py-1 rounded-full text-xs font-bold">
                                        Level ${level}
                                    </span>
                                    ${referral.children && referral.children.length > 0 ? `
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                            ${referral.children.length} referral${referral.children.length > 1 ? 's' : ''}
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        ${referral.children && referral.children.length > 0 ? renderReferralTree(referral.children, level + 1) : ''}
                    </div>
                `;
            });
            
            return html;
        }

        // Copy referral ID
        function copyReferralId(id) {
            navigator.clipboard.writeText(id).then(() => {
                alert('Referral ID copied to clipboard!');
            });
        }

        // Event listeners
        document.getElementById('refreshBtn').addEventListener('click', () => {
            loadReferralTree(currentMaxLevel);
            loadStats();
        });

        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            loadReferralTree(null); // Load all levels
        });

        // Initial load
        loadStats();
        loadReferralTree();
    </script>
</body>
</html>
