<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - YourApp</title>
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
            background-image: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        .neon-border {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
            border: 1px solid rgba(99, 102, 241, 0.5);
        }
        .input-field {
            background-color: #1e293b;
            border: 2px solid #334155;
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            background-color: #0f172a;
            border-color: #6366f1;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.4);
            outline: none;
        }
    </style>
</head>
<body class="antialiased h-screen flex overflow-hidden">

    <!-- Left Side: Gamified Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-[#0f172a] relative z-10 border-r-4 border-indigo-900">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="w-full max-w-md relative z-20">
            <div class="mb-8 text-center">
                <a href="/" class="text-3xl font-black tracking-widest text-white uppercase" style="font-family: 'Courier New', monospace; text-shadow: 2px 2px 0px #4f46e5;">
                    LEARN<span class="text-indigo-400">CODE</span>
                </a>
                <h2 class="mt-6 text-2xl font-bold text-white tracking-wide">Player Login</h2>
                <p class="text-indigo-300 text-sm mt-2">Enter your credentials to resume your session.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="player@example.com" 
                               class="input-field w-full py-3 pl-10 pr-4 rounded-lg">
                    </div>
                    @error('email') <p class="text-red-400 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-500">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="••••••••" 
                               class="input-field w-full py-3 pl-10 pr-4 rounded-lg">
                    </div>
                    @error('password') <p class="text-red-400 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-600 bg-gray-800 text-indigo-600 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <span class="ml-2 text-sm text-gray-400 group-hover:text-indigo-300 transition-colors">Remember me</span>
                    </label>
                    
                    <a href="{{ route('password.reset') }}" class="text-sm font-bold text-indigo-400 hover:text-indigo-300 hover:underline">Lost Key?</a>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02] shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                    ENTER WORLD
                </button>

                <p class="text-center text-sm text-gray-500 mt-6">
                    New Player?
                    <a href="{{ route('register') }}" class="font-bold text-indigo-400 hover:text-indigo-300">Create Character</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Right Side: Visuals -->
    <div class="hidden md:block md:w-1/2 auth-bg relative">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] to-transparent"></div>
        <div class="absolute bottom-10 left-10 text-white z-10 max-w-md">
            <h3 class="text-3xl font-black mb-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">MASTERY AWAITS</h3>
            <p class="text-gray-300">"The only way to learn a new programming language is by writing programs in it."</p>
        </div>
    </div>

</body>
</html>