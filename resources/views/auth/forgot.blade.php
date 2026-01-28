@extends('layouts.frontend')

@section('title', 'Forgot Password - Vajra Green Leaf')

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .auth-page-bg {
        background-color: #052d00;
    }
</style>
@endpush

@section('content')
<div class="auth-page-bg min-h-screen selection:bg-emerald-500 selection:text-white">
    <!-- Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-600/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-green-600/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 flex flex-col justify-center px-6 py-12 lg:px-8 items-center min-h-[calc(100vh-80px)]">
        <div class="w-full max-w-sm space-y-8 glass-card p-10 rounded-3xl shadow-2xl ring-1 ring-white/10">

            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-tr from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Forgot Password?</h2>
                <p class="text-sm text-gray-400">Don't worry, we're here to help</p>
            </div>

            <!-- Contact Admin Message -->
            <div class="rounded-xl bg-amber-500/10 p-6 border border-amber-500/20 text-center">
                <div class="h-12 w-12 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <h3 class="text-amber-400 font-bold text-lg mb-2">Contact Admin</h3>
                <p class="text-gray-300 text-sm mb-4">To reset your password, please contact the administrator directly.</p>

                <div class="space-y-3 text-left bg-white/5 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-white font-medium">{{ $siteSettings['phone'] ?? '+91 XXXXXXXXXX' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-white font-medium">{{ $siteSettings['email'] ?? 'admin@vajragreenleaf.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500 mt-6">
                <a href="{{ route('auth.login') }}" class="font-semibold text-emerald-400 hover:text-emerald-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
