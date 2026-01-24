<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full bg-[#052d00] overflow-hidden relative selection:bg-emerald-500 selection:text-white">
    <!-- Background -->
    <div class="fixed inset-0 z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-600/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-green-600/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8 items-center">
        <div class="w-full max-w-sm space-y-8 glass-card p-10 rounded-3xl shadow-2xl ring-1 ring-white/10">
            
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-tr from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 9.464a7.978 7.978 0 00-6.105-1.554L5.617 8.093A4.978 4.978 0 002.348 3.535l-.898.898L15 7zM5 18a2 2 0 002 2h8a2 2 0 002-2V8.225a6.002 6.002 0 00-1.914 1.34L5 18z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Reset Password</h2>
                <p class="text-sm text-gray-400">Recover your account easily</p>
            </div>

            <!-- Success Message Container -->
            <div id="successBlock" class="hidden rounded-xl bg-emerald-500/10 p-6 border border-emerald-500/20 text-center">
                <div class="h-12 w-12 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-emerald-400 font-bold text-lg">Success!</h3>
                <p class="text-gray-300 text-sm mt-1">Password reset successfully.</p>
                <a href="{{ route('auth.login') }}" class="mt-4 inline-block text-emerald-400 hover:text-emerald-300 font-semibold underline">Login Now</a>
            </div>

            <!-- Step 1: Verify Identity -->
            <form id="step1Form" class="space-y-6" onsubmit="return handleVerify(event)">
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-300">Email or Mobile or Username</label>
                    <div class="mt-2">
                        <input id="identity" type="text" required class="block w-full rounded-xl border-0 bg-white/5 py-3 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6" placeholder="Enter your identity">
                    </div>
                    <p id="errorMsg1" class="mt-2 text-sm text-red-400 hidden"></p>
                </div>
                <div>
                    <button type="submit" id="btnVerify" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:from-emerald-500 hover:to-green-500 transition-all">Verify</button>
                </div>
            </form>

            <!-- Step 2: Reset Password (Hidden) -->
            <form id="step2Form" class="space-y-6 hidden" onsubmit="return handleReset(event)">
                <input type="hidden" id="user_id">
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-300">New Password</label>
                    <div class="mt-2">
                        <input id="password" type="password" required class="block w-full rounded-xl border-0 bg-white/5 py-3 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-300">Confirm Password</label>
                    <div class="mt-2">
                        <input id="password_confirmation" type="password" required class="block w-full rounded-xl border-0 bg-white/5 py-3 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <p id="errorMsg2" class="mt-2 text-sm text-red-400 hidden"></p>
                <div>
                    <button type="submit" id="btnReset" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:from-emerald-500 hover:to-green-500 transition-all">Reset Password</button>
                </div>
            </form>

             <div id="backToLogin" class="text-center text-sm text-gray-500 mt-6">
                <a href="{{ route('auth.login') }}" class="font-semibold text-emerald-400 hover:text-emerald-300">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        async function handleVerify(e) {
            e.preventDefault();
            const identity = document.getElementById('identity').value;
            const btn = document.getElementById('btnVerify');
            const err = document.getElementById('errorMsg1');
            
            btn.disabled = true;
            btn.innerText = 'Verifying...';
            err.classList.add('hidden');

            try {
                const res = await fetch('{{ route('auth.forgot.verify') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ identity })
                });
                const data = await res.json();
                
                if(data.success) {
                    document.getElementById('step1Form').classList.add('hidden');
                    document.getElementById('step2Form').classList.remove('hidden');
                    document.getElementById('user_id').value = data.user_id;
                } else {
                    err.textContent = data.message;
                    err.classList.remove('hidden');
                    btn.disabled = false;
                    btn.innerText = 'Verify';
                }
            } catch (e) {
                err.textContent = 'Server Error';
                err.classList.remove('hidden');
                btn.disabled = false;
                btn.innerText = 'Verify';
            }
        }

        async function handleReset(e) {
            e.preventDefault();
            const userId = document.getElementById('user_id').value;
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const btn = document.getElementById('btnReset');
            const err = document.getElementById('errorMsg2');

            if (password !== confirmation) {
                 err.textContent = "Passwords do not match.";
                 err.classList.remove('hidden');
                 return false;
            }

            btn.disabled = true;
            btn.innerText = 'Resetting...';
            err.classList.add('hidden');

            try {
                const res = await fetch('{{ route('auth.forgot.submit') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ user_id: userId, password: password, password_confirmation: confirmation })
                });
                const data = await res.json();

                if(data.success) {
                    document.getElementById('step2Form').classList.add('hidden');
                    document.getElementById('successBlock').classList.remove('hidden');
                    document.getElementById('backToLogin').classList.add('hidden');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 3000);
                } else {
                     err.textContent = data.message || "Failed to reset.";
                     err.classList.remove('hidden');
                     btn.disabled = false;
                     btn.innerText = 'Reset Password';
                }
            } catch(e) {
                 err.textContent = "Server Error";
                 err.classList.remove('hidden');
                 btn.disabled = false;
                 btn.innerText = 'Reset Password';
            }
        }
    </script>
</body>
</html>
