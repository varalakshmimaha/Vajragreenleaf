@extends('layouts.admin')

@section('title', 'Reports & References')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Reports & References</h1>
    <p class="text-gray-600 mt-2">Comprehensive referral analytics and reporting</p>
</div>

<!-- Summary Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <span class="text-sm font-medium opacity-90">Total</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">{{ number_format($summary['total_users']) }}</h3>
        <p class="text-sm opacity-90">Total Users</p>
    </div>

    <!-- Total References -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <span class="text-sm font-medium opacity-90">Level 1</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">{{ number_format($summary['total_references']) }}</h3>
        <p class="text-sm opacity-90">Direct References</p>
    </div>

    <!-- Total Sub-References -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                <i class="fas fa-sitemap text-2xl"></i>
            </div>
            <span class="text-sm font-medium opacity-90">Level 2+</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">{{ number_format($summary['total_sub_references']) }}</h3>
        <p class="text-sm opacity-90">Sub-References</p>
    </div>

    <!-- Max Depth -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <span class="text-sm font-medium opacity-90">Depth</span>
        </div>
        <h3 class="text-3xl font-bold mb-1">Level {{ $summary['max_depth'] }}</h3>
        <p class="text-sm opacity-90">Maximum Depth</p>
    </div>
</div>

<!-- Additional Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Average Referrals -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
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
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-arrow-trend-up text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Growth Rate (30 days)</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $summary['growth_rate_30d'] }}%</h4>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-user-check text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Active Users</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($summary['active_users']) }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Available Reports</h2>
        <p class="text-sm text-gray-600 mt-1">Select a report type to view detailed analytics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
        <!-- Summary Report -->
        <a href="{{ route('admin.reports.summary') }}" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-200 hover:border-blue-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Summary Report</h3>
            </div>
            <p class="text-sm text-gray-600">Overall referral statistics and key metrics</p>
        </a>

        <!-- Level-Wise Report -->
        <a href="{{ route('admin.reports.level-wise') }}" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 hover:border-green-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-green-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Level-Wise Report</h3>
            </div>
            <p class="text-sm text-gray-600">Distribution of users across referral levels</p>
        </a>

        <!-- User-Wise Report -->
        <a href="{{ route('admin.reports.user-wise') }}" class="group block p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-purple-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">User-Wise Report</h3>
            </div>
            <p class="text-sm text-gray-600">Individual user referral performance</p>
        </a>

        <!-- Drill-Down Report -->
        <a href="{{ route('admin.reports.drill-down') }}" class="group block p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border-2 border-orange-200 hover:border-orange-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-orange-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-sitemap text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Drill-Down Explorer</h3>
            </div>
            <p class="text-sm text-gray-600">Interactive referral tree exploration</p>
        </a>

        <!-- Growth Report -->
        <a href="{{ route('admin.reports.growth') }}" class="group block p-6 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl border-2 border-teal-200 hover:border-teal-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-teal-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Growth Report</h3>
            </div>
            <p class="text-sm text-gray-600">Track referral growth trends over time</p>
        </a>

        <!-- Inactive Users Report -->
        <a href="{{ route('admin.reports.inactive') }}" class="group block p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border-2 border-red-200 hover:border-red-400 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-red-500 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-slash text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Inactive Users</h3>
            </div>
            <p class="text-sm text-gray-600">Identify users with zero or no activity</p>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 flex items-center justify-between bg-gray-50 rounded-xl p-4 border border-gray-200">
    <div class="flex items-center gap-3">
        <i class="fas fa-info-circle text-blue-600"></i>
        <span class="text-sm text-gray-700">Reports are cached for 1 hour for better performance</span>
    </div>
    <form action="{{ route('admin.reports.clear-cache') }}" method="POST">
        @csrf
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors text-sm font-medium">
            <i class="fas fa-sync-alt mr-2"></i>Clear Cache
        </button>
    </form>
</div>
@endsection
