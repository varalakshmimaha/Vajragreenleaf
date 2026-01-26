@extends('layouts.admin')

@section('title', 'Inactive Users Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Inactive Users Report</h1>
            <p class="text-gray-600 mt-1">Identify users with zero or no activity</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('admin.reports.inactive.export', array_merge(['format' => 'csv'], $filters)) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.reports.inactive') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Inactive Days</label>
            <input type="number" name="inactive_days" value="{{ $filters['inactive_days'] ?? 30 }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Referral Status</label>
            <select name="has_referrals" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Users</option>
                <option value="zero" {{ ($filters['has_referrals'] ?? '') === 'zero' ? 'selected' : '' }}>Zero Referrals</option>
                <option value="no_sub" {{ ($filters['has_referrals'] ?? '') === 'no_sub' ? 'selected' : '' }}>No Sub-Referrals</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.reports.inactive') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-user-slash text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Inactive Users</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($users->total()) }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-orange-100 rounded-lg">
                <i class="fas fa-calendar-xmark text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Threshold</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $filters['inactive_days'] ?? 30 }}</h4>
                <p class="text-xs text-gray-500">days</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-filter text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Filter Applied</p>
                <h4 class="text-lg font-bold text-gray-900">
                    @if(empty($filters['has_referrals']))
                        All Users
                    @elseif($filters['has_referrals'] === 'zero')
                        Zero Referrals
                    @else
                        No Sub-Refs
                    @endif
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Inactive Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Inactive Users</h2>
        <p class="text-sm text-gray-600 mt-1">Users with no recent activity</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Referral ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Direct Refs</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sub Refs</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Days Inactive</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Last Login</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Registered</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-lg font-mono text-sm">
                                {{ $user->referral_id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold {{ $user->referrals_count > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->referrals_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold {{ $user->sub_referrals_count > 0 ? 'text-purple-600' : 'text-gray-400' }}">
                                {{ $user->sub_referrals_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 {{ $user->days_inactive > 60 ? 'bg-red-100 text-red-800' : ($user->days_inactive > 30 ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }} rounded-full text-sm font-medium">
                                {{ $user->days_inactive }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-3 text-green-400 opacity-50"></i>
                            <p class="text-lg font-medium">No inactive users found</p>
                            <p class="text-sm mt-1">All users are active!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Insights -->
<div class="mt-6 bg-orange-50 rounded-xl p-6 border border-orange-200">
    <div class="flex items-start gap-4">
        <div class="p-3 bg-orange-600 rounded-lg">
            <i class="fas fa-lightbulb text-white text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-gray-900 mb-2">Engagement Insights</h3>
            <p class="text-sm text-gray-700">
                @if($users->total() > 0)
                    Found <strong>{{ number_format($users->total()) }} inactive users</strong> who haven't logged in for more than {{ $filters['inactive_days'] ?? 30 }} days. 
                    Consider re-engagement campaigns to bring them back.
                @else
                    Great! No users are inactive for more than {{ $filters['inactive_days'] ?? 30 }} days. Your user base is highly engaged!
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
