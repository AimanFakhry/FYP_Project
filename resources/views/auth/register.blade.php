<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - YourApp</title>
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
            background-image: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
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
            border-color: #6366f1;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.4);
            outline: none;
        }
    </style>
</head>
<body class="antialiased h-screen flex overflow-hidden">

    <!-- Left Side: Visuals (Swapped for Register) -->
    <div class="hidden md:block md:w-1/2 auth-bg relative order-2 md:order-1">
        <div class="absolute inset-0 bg-gradient-to-l from-[#0f172a] to-transparent"></div>
        <div class="absolute top-10 left-10">
            <a href="/" class="text-2xl font-black tracking-widest text-white uppercase" style="font-family: 'Courier New', monospace; text-shadow: 2px 2px 0px #4f46e5;">
                LEARN<span class="text-indigo-400">CODE</span>
            </a>
        </div>
        <div class="absolute bottom-10 left-10 text-white z-10 max-w-md">
            <h3 class="text-3xl font-black mb-2 text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">JOIN THE SERVER</h3>
            <p class="text-gray-300">Begin your journey. Choose your path. Code your future.</p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-[#0f172a] relative z-10 border-l-4 border-indigo-900 order-1 md:order-2">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="w-full max-w-md relative z-20">
            <div class="mb-8 text-center md:text-left">
                <h2 class="text-3xl font-bold text-white tracking-wide mb-2">Create Character</h2>
                <p class="text-gray-400">Fill in your details to start your adventure.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Username</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="HeroName123" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                    @error('name') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="hero@example.com" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                    @error('email') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                    @error('password') <p class="text-red-400 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" 
                           class="input-field w-full py-3 px-4 rounded-lg mt-1">
                </div>

                <button type="submit" class="w-full py-3 px-4 mt-6 border border-transparent rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.4)] text-sm font-bold text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-[1.02]">
                    SPAWN CHARACTER
                </button>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300">Login here</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>