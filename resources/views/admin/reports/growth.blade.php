@extends('layouts.admin')

@section('title', 'Growth Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Growth Report</h1>
            <p class="text-gray-600 mt-1">Track referral growth trends over time</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('admin.reports.growth', array_merge(request()->all(), ['export' => 'excel'])) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
            <a href="{{ route('admin.reports.growth', array_merge(request()->all(), ['export' => 'csv'])) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Period Selector with Date Range -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.reports.growth') }}" method="GET" class="space-y-4">
        <div class="flex items-center gap-4 flex-wrap">
            <label class="text-sm font-medium text-gray-700">View Period:</label>
            <div class="flex gap-2">
                <button type="submit" name="period" value="daily" class="px-4 py-2 {{ $period === 'daily' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                    Daily
                </button>
                <button type="submit" name="period" value="weekly" class="px-4 py-2 {{ $period === 'weekly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                    Weekly
                </button>
                <button type="submit" name="period" value="monthly" class="px-4 py-2 {{ $period === 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                    Monthly
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>Apply Date Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Growth</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($growthData['total_growth']) }}</h4>
                <p class="text-xs text-gray-500">new users</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-calendar text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Time Period</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ ucfirst($period) }}</h4>
                <p class="text-xs text-gray-500">{{ count($growthData['timeline']) }} data points</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-arrow-trend-up text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Average per Period</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ count($growthData['timeline']) > 0 ? number_format($growthData['total_growth'] / count($growthData['timeline']), 1) : 0 }}</h4>
                <p class="text-xs text-gray-500">users/{{ $period === 'daily' ? 'day' : ($period === 'weekly' ? 'week' : 'month') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Chart -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Growth Timeline</h2>
        <p class="text-sm text-gray-600 mt-1">User registrations over time</p>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Period</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">New Users</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Visual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($growthData['timeline'] as $point)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $point['label'] }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-blue-600">{{ number_format($point['count']) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        @php
                                            $maxCount = max(array_column($growthData['timeline'], 'count'));
                                            $percentage = $maxCount > 0 ? ($point['count'] / $maxCount) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Level-Wise Growth -->
@if(!empty($growthData['level_growth']))
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Level-Wise Growth</h2>
        <p class="text-sm text-gray-600 mt-1">New users by referral level</p>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($growthData['level_growth'] as $levelGrowth)
                <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                    <div class="text-sm font-medium text-gray-600 mb-2">Level {{ $levelGrowth['level'] }}</div>
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($levelGrowth['new_users']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">new users</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
