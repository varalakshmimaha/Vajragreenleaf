@extends('layouts.admin')

@section('title', 'Edit Customer - ' . $customer->name)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 transition-colors mb-4 font-bold text-sm uppercase tracking-wider">
            <i class="fas fa-arrow-left mr-2"></i> Back to Customers
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Customer</h1>
        <p class="text-gray-600">Update customer information</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}" required pattern="[0-9]{10}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Sponsor Id:</span>
                            <span class="font-bold text-emerald-600 font-mono ml-2">{{ $customer->referral_id ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Sponsor By:</span>
                            <span class="font-bold text-purple-600 font-mono ml-2">{{ $customer->sponsor_referral_id ?? 'None' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Registered:</span>
                            <span class="font-medium ml-2">{{ $customer->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password <span class="text-gray-400 text-xs">(leave blank to keep current)</span></label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                           class="h-5 w-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                    <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">Account Active</label>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4">
                <a href="{{ route('admin.customers.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
@endsection
