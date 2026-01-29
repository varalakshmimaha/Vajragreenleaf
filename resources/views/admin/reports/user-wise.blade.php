@extends('layouts.admin')

@section('title', 'User-Wise Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User-Wise Report</h1>
            <p class="text-gray-600 mt-1">Individual user referral performance</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('admin.reports.user-wise.export', array_merge(['format' => 'csv'], $filters)) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.reports.user-wise') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sponsor Id</label>
            <input type="text" name="referral_id" value="{{ $filters['referral_id'] ?? '' }}" placeholder="Search ID..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Min Referrals</label>
            <input type="number" name="min_referrals" value="{{ $filters['min_referrals'] ?? '' }}" placeholder="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Max Referrals</label>
            <input type="number" name="max_referrals" value="{{ $filters['max_referrals'] ?? '' }}" placeholder="100" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.reports.user-wise') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">User Performance</h2>
        <p class="text-sm text-gray-600 mt-1">Showing {{ $users->total() }} users</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sponsor Id</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Level</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Direct</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Sub-Refs</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total Network</th>
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
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-mono text-sm font-bold">
                                {{ $user->referral_id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                Level {{ $user->level }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-green-600">{{ $user->direct_referrals }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-purple-600">{{ $user->sub_referrals }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-blue-600">{{ $user->total_network }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users-slash text-4xl mb-3 opacity-20"></i>
                            <p>No users found matching your criteria</p>
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
@endsection
