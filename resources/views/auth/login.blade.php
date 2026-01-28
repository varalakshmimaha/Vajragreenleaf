@extends('layouts.frontend')

@section('title', 'Login - Vajra Green Leaf')

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
    <!-- Decorative Ambient Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70%] h-[70%] rounded-full bg-emerald-600/5 blur-[150px]"></div>
    </div>

    <div class="relative z-10 flex flex-col justify-center px-6 py-12 lg:px-8 items-center min-h-[calc(100vh-80px)]">
        <div class="w-full max-w-sm space-y-8 glass-card p-10 rounded-3xl shadow-2xl ring-1 ring-white/10">

            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-tr from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Welcome Back</h2>
                <p class="text-sm text-gray-400">Sign in to your account</p>
            </div>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 p-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm text-red-300">{{ $errors->first() }}</span>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('auth.login.submit') }}" method="POST">
                @csrf
                <div>
                    <label for="mobile" class="block text-sm font-medium leading-6 text-gray-300">Mobile Number</label>
                    <div class="mt-2">
                        <input id="mobile" name="mobile" type="tel" pattern="[0-9]{10}" autocomplete="tel" required class="block w-full rounded-xl border-0 bg-white/5 py-3 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 transition-all" placeholder="Enter 10 digit mobile number" value="{{ old('mobile') }}">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-300">Password</label>
                        <div class="text-sm">
                            <a href="{{ route('auth.forgot') }}" class="font-medium text-emerald-400 hover:text-green-400">Forgot password?</a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-xl border-0 bg-white/5 py-3 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 transition-all" placeholder="Enter your password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:from-emerald-500 hover:to-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-all transform hover:scale-[1.02] active:scale-[0.98]">Sign in</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-400">
                New User?
                <a href="{{ route('auth.register') }}" class="font-semibold leading-6 text-emerald-400 hover:text-green-400">Register / Sign Up</a>
            </p>
        </div>
    </div>
</div>
@endsection
