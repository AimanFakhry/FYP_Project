<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - YourApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Figtree', sans-serif;
        }
        .auth-bg {
            background-image: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        .input-field {
            background-color: #1e293b;
            border: 2px solid #334155;
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            background-color: #0f172a;
            border-color: #f472b6; /* Pink for reset focus */
            box-shadow: 0 0 10px rgba(244, 114, 182, 0.4);
            outline: none;
        }
    </style>
</head>
<body class="antialiased h-screen flex overflow-hidden">

    <!-- Left Side: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-[#0f172a] relative z-10 border-r-4 border-indigo-900">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#f472b6 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="w-full max-w-md relative z-20">
            <!-- Navbar Minimal -->
            <nav class="absolute top-0 left-0 w-full flex justify-between items-center -mt-20 mb-10">
                <a href="/" class="text-2xl font-black tracking-widest text-white uppercase" style="font-family: 'Courier New', monospace; text-shadow: 2px 2px 0px #4f46e5;">
                    LEARN<span class="text-indigo-400">CODe</span>
                </a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white font-bold text-sm">Cancel</a>
            </nav>

            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 text-pink-500 mb-4 border-2 border-gray-700 shadow-[0_0_15px_rgba(244,114,182,0.3)]">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-wide">Reset Key</h2>
                <p class="text-gray-400 text-sm mt-2">Enter your credentials and security token to restore access.</p>
            </div>

            @if (session('status'))
                <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-3 rounded-lg mb-6 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="player@example.com"
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                    @error('email') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- ADDED SECURITY TOKEN FIELD -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-red-400 uppercase tracking-wider ml-1 flex justify-between">
                        <span>Security Token</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="security_token" required placeholder="ABCD12" maxlength="6"
                               class="input-field w-full py-3 px-4 rounded-lg border-red-900/50 focus:border-red-500 font-mono tracking-widest uppercase">
                    </div>
                    @error('security_token') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">New Password</label>
                    <input type="password" name="password" required placeholder="••••••••" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                    @error('password') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                </div>

                <button type="submit" class="w-full py-3 px-4 mt-6 border border-transparent rounded-lg shadow-[0_0_15px_rgba(236,72,153,0.4)] text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-[1.02]">
                    RESTORE ACCESS
                </button>
            </form>
        </div>
    </div>

    <!-- Right Side: Visuals -->
    <div class="hidden md:block md:w-1/2 auth-bg relative">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] to-transparent"></div>
        <div class="absolute bottom-10 right-10 text-white z-10 max-w-md text-right">
            <h3 class="text-3xl font-black mb-2 text-transparent bg-clip-text bg-gradient-to-l from-pink-400 to-purple-400">SECURE SYSTEM</h3>
            <p class="text-gray-300">"Security is not a product, but a process. Reclaim your account and continue the mission."</p>
        </div>
    </div>

</body>
</html>