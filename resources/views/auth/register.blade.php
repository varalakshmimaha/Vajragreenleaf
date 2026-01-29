@extends('layouts.frontend')

@section('title', 'Registration - Join Vajra Green Leaf')

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .form-input {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 0.75rem;
    }
    .form-input:focus {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: #10b981;
        ring-color: #10b981;
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
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70%] h-[70%] rounded-full bg-emerald-600/5 blur-[150px]"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-12 flex justify-center items-center min-h-[calc(100vh-80px)]">
        <div class="w-full max-w-md glass-card rounded-3xl shadow-2xl p-6 md:p-10 ring-1 ring-white/10">

            <form id="registerForm" class="space-y-6" onsubmit="return handleRegister(event)">
                @csrf

                <div class="text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-tr from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Create Account</h2>
                    <p class="text-sm text-gray-400">Join Vajra Green Leaf today</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Full Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter your full name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Mobile Number <span class="text-red-400">*</span></label>
                        <input type="tel" name="mobile" required pattern="[0-9]{10}" class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter 10 digit mobile number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Sponsor Id <span class="text-gray-500 text-xs">(Optional)</span></label>
                        <input type="text" name="sponsor_id" class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter sponsor ID if you have one">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password" required minlength="8" class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Minimum 8 characters">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password_confirmation" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Re-enter your password">
                    </div>
                </div>

                <!-- Error Message -->
                <div id="registerError" class="hidden rounded-md bg-red-500/10 p-4 border border-red-500/20">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-400" id="registerErrorText">Error submitting form</h3>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" id="submitBtn" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-3 py-3.5 text-sm font-bold tracking-wide uppercase text-white shadow-lg hover:from-emerald-500 hover:to-green-500 transition-all transform hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50">
                        Register
                    </button>
                    <p class="text-center mt-6 text-gray-400 text-sm">
                        Already have an account? <a href="{{ route('auth.login') }}" class="text-emerald-400 hover:underline font-semibold">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function handleRegister(e) {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        const errorDiv = document.getElementById('registerError');
        const errorText = document.getElementById('registerErrorText');

        const password = document.querySelector('input[name="password"]').value;
        const confirm = document.querySelector('input[name="password_confirmation"]').value;

        if (password !== confirm) {
            errorText.textContent = "Passwords do not match.";
            errorDiv.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Processing...';
        errorDiv.classList.add('hidden');

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('{{ route('auth.register.submit') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.location.href = result.redirect_url;
            } else {
                throw new Error(result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Registration failed'));
            }

        } catch (err) {
            btn.disabled = false;
            btn.textContent = 'Register';
            errorText.textContent = err.message;
            errorDiv.classList.remove('hidden');
        }
    }
</script>
@endsection
