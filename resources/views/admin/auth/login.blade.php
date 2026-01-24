<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Vajra Green Leaf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-emerald-600">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Vajra Green Leaf</h1>
                    <p class="text-gray-500 mt-2 font-medium uppercase text-xs tracking-widest">Admin Control Panel</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        @foreach($errors->all() as $error)
                            <p class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Address</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            value="{{ old('email') }}" placeholder="admin@vajragreenleaf.com">
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-emerald-700 transition-colors">Remember my session</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 shadow-lg shadow-emerald-200 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-emerald-700 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        Secure Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
