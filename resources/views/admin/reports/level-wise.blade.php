@extends('layouts.admin')

@section('title', 'Level-Wise Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Level-Wise Report</h1>
            <p class="text-gray-600 mt-1">Distribution of users across referral levels</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
            <a href="{{ route('admin.reports.level-wise.export', ['format' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-layer-group text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Levels</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ count($levelData) }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Users</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format(array_sum(array_column($levelData, 'count'))) }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-crown text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Largest Level</p>
                @php
                    $maxLevel = collect($levelData)->sortByDesc('count')->first();
                @endphp
                <h4 class="text-2xl font-bold text-gray-900">Level {{ $maxLevel['level'] ?? 'N/A' }}</h4>
                <p class="text-xs text-gray-500">{{ number_format($maxLevel['count'] ?? 0) }} users</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-orange-100 rounded-lg">
                <i class="fas fa-chart-pie text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Average per Level</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ count($levelData) > 0 ? number_format(array_sum(array_column($levelData, 'count')) / count($levelData), 1) : 0 }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Level Distribution Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Level Distribution</h2>
        <p class="text-sm text-gray-600 mt-1">Detailed breakdown of users at each referral level</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Level</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Users Count</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Percentage</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($levelData as $index => $level)
                    <tr class="hover:bg-gray-50 transition-colors {{ $index >= 2 ? 'hidden level-row' : '' }}" data-level="{{ $index }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg text-white font-bold text-sm">
                                    L{{ $level['level'] }}
                                </div>
                                <span class="font-medium text-gray-900">Level {{ $level['level'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($level['count']) }}</span>
                            <span class="text-sm text-gray-500 ml-2">users</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 {{ $level['type'] === 'Direct' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }} rounded-full text-sm font-medium">
                                <i class="fas fa-{{ $level['type'] === 'Direct' ? 'user-plus' : 'sitemap' }} mr-2"></i>
                                {{ $level['type'] }} Reference
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold text-gray-900">{{ $level['percentage'] }}%</span>
                                <span class="text-sm text-gray-500">of total</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $level['percentage'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-12 text-right">{{ $level['percentage'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-20"></i>
                            <p class="text-lg font-medium">No level data available</p>
                            <p class="text-sm mt-1">Start building your referral network to see level distribution</p>
                        </td>
                    </tr>
                @endforelse
                
                @if(count($levelData) > 2)
                    <tr id="loadMoreRow" class="bg-gray-50">
                        <td colspan="5" class="px-6 py-4 text-center">
                            <button onclick="loadMoreLevels()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-chevron-down mr-2"></i>
                                Load More Levels ({{ count($levelData) - 2 }} more)
                            </button>
                        </td>
                    </tr>
                @endif
            </tbody>
            @if(count($levelData) > 0)
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td class="px-6 py-4 font-bold text-gray-900">Total</td>
                        <td class="px-6 py-4">
                            <span class="text-2xl font-bold text-blue-600">{{ number_format(array_sum(array_column($levelData, 'count'))) }}</span>
                            <span class="text-sm text-gray-500 ml-2">users</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">All Types</td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-gray-900">100%</span>
                        </td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

<!-- Insights -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Distribution Insight -->
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-600 rounded-lg">
                <i class="fas fa-lightbulb text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 mb-2">Distribution Insight</h3>
                <p class="text-sm text-gray-700">
                    @if(count($levelData) > 0)
                        @php
                            $directCount = collect($levelData)->where('type', 'Direct')->sum('count');
                            $subCount = collect($levelData)->where('type', 'Sub')->sum('count');
                            $total = $directCount + $subCount;
                        @endphp
                        Your network has <strong>{{ number_format($directCount) }} direct references</strong> and 
                        <strong>{{ number_format($subCount) }} sub-references</strong> across 
                        <strong>{{ count($levelData) }} levels</strong>.
                    @else
                        No referral data available yet.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Growth Potential -->
    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-green-600 rounded-lg">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 mb-2">Growth Potential</h3>
                <p class="text-sm text-gray-700">
                    @if(count($levelData) > 0)
                        The network spans <strong>{{ count($levelData) }} levels deep</strong>. 
                        @if(count($levelData) >= 5)
                            Excellent depth! Your network is well-established.
                        @elseif(count($levelData) >= 3)
                            Good progress! Continue building deeper levels.
                        @else
                            Early stage. Focus on expanding to deeper levels.
                        @endif
                    @else
                        Start building your referral network to unlock growth potential.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentlyShown = 2;
const totalLevels = {{ count($levelData) }};

function loadMoreLevels() {
    const hiddenRows = document.querySelectorAll('.level-row.hidden');
    const loadMoreButton = document.querySelector('#loadMoreRow button');
    const loadMoreRow = document.getElementById('loadMoreRow');
    
    // Show next 5 levels or remaining levels
    const toShow = Math.min(5, hiddenRows.length);
    
    for (let i = 0; i < toShow; i++) {
        hiddenRows[i].classList.remove('hidden');
        // Add fade-in animation
        hiddenRows[i].style.animation = 'fadeIn 0.3s ease-in';
    }
    
    currentlyShown += toShow;
    
    // Update button text or hide it
    const remaining = totalLevels - currentlyShown;
    if (remaining > 0) {
        loadMoreButton.innerHTML = `<i class="fas fa-chevron-down mr-2"></i>Load More Levels (${remaining} more)`;
    } else {
        loadMoreRow.style.display = 'none';
    }
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
