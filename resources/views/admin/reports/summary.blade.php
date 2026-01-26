@extends('layouts.admin')

@section('title', 'Summary Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Summary Report</h1>
            <p class="text-gray-600 mt-1">Overall referral statistics and key metrics</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
            <a href="{{ route('admin.reports.summary.export', ['format' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-users text-blue-600 text-2xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-500">Total</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($summary['total_users']) }}</h3>
        <p class="text-sm text-gray-600">Total Users</p>
    </div>

    <!-- Direct References -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-user-plus text-green-600 text-2xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-500">Level 1</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($summary['total_references']) }}</h3>
        <p class="text-sm text-gray-600">Direct References</p>
    </div>

    <!-- Sub-References -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-sitemap text-purple-600 text-2xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-500">Level 2+</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($summary['total_sub_references']) }}</h3>
        <p class="text-sm text-gray-600">Sub-References</p>
    </div>

    <!-- Max Depth -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-orange-100 rounded-lg">
                <i class="fas fa-layer-group text-orange-600 text-2xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-500">Depth</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-1">Level {{ $summary['max_depth'] }}</h3>
        <p class="text-sm text-gray-600">Maximum Depth</p>
    </div>
</div>

<!-- Additional Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Average Referrals -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Average Referrals/User</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $summary['avg_referrals_per_user'] }}</h4>
            </div>
        </div>
    </div>

    <!-- Growth Rate -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 {{ $summary['growth_rate_30d'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg">
                <i class="fas fa-arrow-trend-{{ $summary['growth_rate_30d'] >= 0 ? 'up' : 'down' }} {{ $summary['growth_rate_30d'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Growth Rate (30 days)</p>
                <h4 class="text-2xl font-bold {{ $summary['growth_rate_30d'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $summary['growth_rate_30d'] }}%</h4>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-user-check text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Active Users</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($summary['active_users']) }}</h4>
                <p class="text-xs text-gray-500">{{ number_format($summary['inactive_users']) }} inactive</p>
            </div>
        </div>
    </div>
</div>

<!-- Level-Wise Distribution Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Level-Wise Distribution</h2>
        <p class="text-sm text-gray-600 mt-1">User distribution across referral levels</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Level</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Users Count</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Percentage</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Visual</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($levelData as $level)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Level {{ $level['level'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-semibold">
                            {{ number_format($level['count']) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 {{ $level['type'] === 'Direct' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }} rounded-full text-sm">
                                {{ $level['type'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ $level['percentage'] }}%
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $level['percentage'] }}%"></div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-20"></i>
                            <p>No level data available</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Summary Stats -->
<div class="mt-6 bg-blue-50 rounded-xl p-6 border border-blue-200">
    <div class="flex items-start gap-4">
        <div class="p-3 bg-blue-600 rounded-lg">
            <i class="fas fa-info-circle text-white text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-gray-900 mb-2">Report Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <strong>Total Network Size:</strong> {{ number_format($summary['total_users']) }} users
                </div>
                <div>
                    <strong>Root Users:</strong> {{ number_format($summary['root_users']) }} users
                </div>
                <div>
                    <strong>Network Depth:</strong> {{ $summary['max_depth'] }} levels
                </div>
                <div>
                    <strong>Referral Ratio:</strong> {{ $summary['total_users'] > 0 ? round(($summary['total_references'] / $summary['total_users']) * 100, 2) : 0 }}% have sponsors
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
