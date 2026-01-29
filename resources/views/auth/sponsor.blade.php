<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Us - Verify Sponsor</title>
    @php
        try {
            $viteAssets = vite(['resources/css/app.css', 'resources/js/app.js'])->toHtml();
        } catch (\Throwable $e) {
            $viteAssets = null;
        }
    @endphp

    @if($viteAssets)
        {!! $viteAssets !!}
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
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
<body class="h-full bg-[#0f172a] overflow-hidden relative selection:bg-purple-500 selection:text-white">
    <!-- Decorative Ambient Background -->
    <div class="fixed inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-purple-600/20 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/20 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8 items-center">
        <div class="w-full max-w-md space-y-8 glass-card p-10 rounded-3xl shadow-2xl ring-1 ring-white/10 transform transition-all duration-500 hover:shadow-purple-500/20">
            
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-tr from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Join Our Community</h2>
                <p class="text-sm text-gray-400">Enter Sponsor By ID or skip to continue (Optional)</p>
            </div>

            <form id="sponsorForm" class="mt-8 space-y-6" onsubmit="return false;">
                <div>
                    <label for="sponsor_id" class="block text-sm font-medium leading-6 text-gray-300">Sponsor By <span class="text-gray-500 font-normal">(Optional)</span></label>
                    <div class="mt-2 relative">
                        <input id="sponsor_id" name="sponsor_id" type="text" class="block w-full rounded-xl border-0 bg-white/5 py-3.5 px-4 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-purple-500 sm:text-sm sm:leading-6 transition-all" placeholder="Enter Sponsor Id (Optional)">
                        <div id="loader" class="absolute right-4 top-3.5 hidden">
                            <svg class="animate-spin h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <p id="error-msg" class="mt-2 text-sm text-red-400 hidden flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Invalid Sponsor</span>
                    </p>
                </div>

                <!-- Sponsor Details Card (Hidden initially) -->
                <div id="sponsorDetails" class="hidden transform transition-all duration-500">
                    <div class="bg-gradient-to-r from-purple-500/20 to-indigo-500/20 border border-purple-500/30 rounded-xl p-4 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-lg" id="sponsorAvatar">
                            S
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Sponsor By Found</p>
                            <h3 class="text-white font-semibold" id="sponsorName">Name</h3>
                            <span class="inline-flex items-center rounded-full bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20 mt-1" id="sponsorStatus">Active</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button type="submit" id="submitBtn" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:from-purple-500 hover:to-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        Verify Sponsor By
                    </button>
                    
                    <a href="#" id="continueBtn" class="hidden flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-lg hover:from-emerald-400 hover:to-teal-400 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        Continue to Registration &rarr;
                    </a>
                    
                    <button type="button" id="skipBtn" class="flex w-full justify-center rounded-xl border-2 border-white/20 bg-transparent px-3 py-3.5 text-sm font-semibold leading-6 text-gray-300 hover:bg-white/5 hover:border-white/30 hover:text-white transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        Skip - Continue Without Sponsor
                    </button>
                </div>
            </form>
            
            <div class="text-center text-sm text-gray-500">
                Already have an account? <a href="{{ route('auth.login') }}" class="font-semibold text-purple-400 hover:text-purple-300">Sign in</a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('sponsorForm');
        const input = document.getElementById('sponsor_id');
        const loader = document.getElementById('loader');
        const submitBtn = document.getElementById('submitBtn');
        const continueBtn = document.getElementById('continueBtn');
        const errorMsg = document.getElementById('error-msg');
        const details = document.getElementById('sponsorDetails');
        
        // Handle Verify
        submitBtn.addEventListener('click', async () => {
            const val = input.value.trim();
            if(!val) return;

            loader.classList.remove('hidden');
            errorMsg.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');

            try {
                const response = await fetch('{{ route('auth.sponsor.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ sponsor_id: val })
                });
                
                const data = await response.json();
                
                loader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');

                if (response.ok && data.success) {
                   // Show Success State
                   document.getElementById('sponsorName').textContent = data.name;
                   document.getElementById('sponsorStatus').textContent = data.status;
                   document.getElementById('sponsorAvatar').textContent = data.name.charAt(0).toUpperCase();
                   
                   details.classList.remove('hidden');
                   submitBtn.classList.add('hidden');
                   continueBtn.classList.remove('hidden');
                   // Use referral_id if available, otherwise fall back to input value
                   const sponsorId = data.referral_id || val;
                   continueBtn.href = "{{ route('auth.register') }}?sponsor=" + encodeURIComponent(sponsorId);
                   
                   input.classList.add('border-green-500', 'text-green-500');
                   // Animate
                   details.classList.add('animate-fadeIn');
                } else {
                    throw new Error(data.message || 'Invalid Sponsor');
                }

            } catch (err) {
                loader.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
                errorMsg.classList.remove('hidden');
                errorMsg.querySelector('span').textContent = err.message || 'Sponsor Id not found';
                details.classList.add('hidden');
            }
        });

        // Reset if user types again
        input.addEventListener('input', () => {
             if(!submitBtn.classList.contains('hidden')) return; // If already verified, wait? No, allow re-verify
             // Actually if they type, we should reset to "Verify" state
             submitBtn.classList.remove('hidden');
             continueBtn.classList.add('hidden');
             details.classList.add('hidden');
             input.classList.remove('border-green-500', 'text-green-500');
        });

        // Skip Button - Allow registration without sponsor
        const skipBtn = document.getElementById('skipBtn');
        skipBtn.addEventListener('click', () => {
            window.location.href = "{{ route('auth.register') }}";
        });
    </script>
</body>
</html>
