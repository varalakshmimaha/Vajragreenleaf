@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Services</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['services'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['products'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Portfolio Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['portfolios'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Blog Posts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['blogs'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Items -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Service Enquiries</h3>
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-medium">{{ $stats['service_enquiries'] }} pending</span>
            </div>
            <a href="{{ route('admin.enquiries.index', ['type' => 'service']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Product Enquiries</h3>
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-medium">{{ $stats['product_enquiries'] }} pending</span>
            </div>
            <a href="{{ route('admin.enquiries.index', ['type' => 'product']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Contact Messages</h3>
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm font-medium">{{ $stats['contact_submissions'] }} new</span>
            </div>
            <a href="{{ route('admin.enquiries.index', ['type' => 'contact']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Enquiries -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-900">Recent Service Enquiries</h3>
            </div>
            <div class="p-6">
                @if($recentEnquiries->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentEnquiries as $enquiry)
                            <div class="flex items-center justify-between py-3 border-b last:border-0">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $enquiry->mobile }}</p>
                                    <p class="text-sm text-gray-500">{{ $enquiry->service?->name ?? 'N/A' }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $enquiry->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No recent enquiries</p>
                @endif
            </div>
        </div>

        <!-- Recent Contacts -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-900">Recent Contact Messages</h3>
            </div>
            <div class="p-6">
                @if($recentContacts->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentContacts as $contact)
                            <div class="flex items-center justify-between py-3 border-b last:border-0">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $contact->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($contact->subject ?? $contact->message, 40) }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $contact->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No recent messages</p>
                @endif
            </div>
        </div>
    </div>
@endsection
