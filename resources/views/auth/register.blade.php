<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - Join Vajra Green Leaf</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
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
    </style>
</head>
<body class="h-full bg-[#052d00] selection:bg-emerald-500 selection:text-white">
    
    <!-- Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-green-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
        <!-- Center Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70%] h-[70%] rounded-full bg-emerald-600/5 blur-[150px]"></div>
    </div>

    <div class="relative z-10 min-h-screen container mx-auto px-4 py-12 flex justify-center items-center">
        <div class="w-full max-w-4xl glass-card rounded-3xl shadow-2xl p-6 md:p-10 ring-1 ring-white/10">
            
            <form id="registerForm" class="space-y-8" onsubmit="return handleRegister(event)">
                @csrf
                
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Create Your Account</h2>
                    <p class="text-sm text-gray-400">Join the organic revolution with Vajra Green Leaf</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Details -->
                    <div class="space-y-5">
                        <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2">Personal Details</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Full Name (Required)</label>
                            <input type="text" name="name" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter Full Name">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Mobile Number (Required)</label>
                            <input type="tel" name="mobile" required pattern="[0-9]{10}" class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter 10 Digit Mobile">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Email Address (Required)</label>
                            <input type="email" name="email" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Enter Email Id">
                        </div>
                    </div>

                    <!-- Account Security -->
                    <div class="space-y-5">
                        <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2">Account Security</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                            <input type="password" name="password" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="••••••••">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="space-y-5 md:col-span-2">
                         <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2">Address Information</h3>
                         
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                                <textarea name="address" rows="2" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="Street, Colony, House No."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">State / City</label>
                                <input type="text" name="state" required class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="e.g. Karnataka / Bangalore">
                                <input type="hidden" name="city" value="DEFAULT">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Pincode</label>
                                <input type="text" name="pincode" required pattern="[0-9]{6}" class="form-input block w-full shadow-sm sm:text-sm py-3 px-4" placeholder="500001">
                            </div>
                         </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600 bg-white/10 border-white/20">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-300">I agree to the <a href="#" class="text-emerald-400 hover:text-green-400 underline">Terms and Conditions</a></label>
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
                     <button type="submit" id="submitBtn" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-3 py-4 text-sm font-bold tracking-wide uppercase text-white shadow-lg hover:from-emerald-500 hover:to-green-500 transition-all transform hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50">
                        Register / Sign Up
                    </button>
                    <p class="text-center mt-6 text-gray-400 text-sm">
                        Already have an account? <a href="{{ route('auth.login') }}" class="text-emerald-400 hover:underline">Sign In</a>
                    </p>
                </div>
            </form>
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
                    window.location.href = result.redirect_url + "?id=" + result.user_id + "&name=" + encodeURIComponent(result.user_name);
                } else {
                    throw new Error(result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Registration failed'));
                }

            } catch (err) {
                btn.disabled = false;
                btn.textContent = 'Register / Sign Up';
                errorText.textContent = err.message;
                errorDiv.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
