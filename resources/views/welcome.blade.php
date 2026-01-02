<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome to YourApp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
            color: #e2e8f0;
            font-family: 'Figtree', sans-serif;
        }
        .hero-bg {
            /* Lighter, purple-tinted background to separate from the dark courses section */
            background-color: #1e1b4b; 
            background-image: 
                radial-gradient(circle at 50% 30%, rgba(99, 102, 241, 0.2) 0%, transparent 70%),
                url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23818cf8" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.4);
            border-color: #6366f1;
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(99, 102, 241, 0.6);
        }
        /* Gamified Button Animation */
        @keyframes bounce-x {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }
        .hover-bounce-x:hover span {
            display: inline-block;
            animation: bounce-x 1s infinite;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navbar Component -->
    @include('partials.navbar')

    <!-- Hero Section -->
    <!-- Updated background style for separation and gamified feel -->
    <div class="relative h-[85vh] flex items-center justify-center pt-16 hero-bg border-b-4 border-indigo-900 shadow-2xl">
        
        <!-- Floating decorative shapes -->
        <div class="absolute top-20 left-10 w-24 h-24 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute top-20 right-10 w-24 h-24 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-32 h-32 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>

        <div class="text-center p-6 max-w-4xl mx-auto z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-800/50 border border-indigo-400 text-indigo-200 text-sm font-bold tracking-widest uppercase mb-6 animate-pulse shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                Start Your Quest
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight tracking-tight drop-shadow-xl">
                LEVEL UP YOUR <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-cyan-400 text-glow">CODING SKILLS</span>
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                Unlock your potential with interactive challenges and gamified learning. Join a community of learners and start your journey today.
            </p>
            
            <div class="flex justify-center gap-6">
                <a href="/register" class="group inline-flex items-center px-8 py-4 bg-indigo-600 text-white font-bold text-lg rounded-xl hover:bg-indigo-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.6)] transition-all transform hover:scale-105 border-b-4 border-indigo-900 active:border-b-0 active:translate-y-1 hover-bounce-x">
                    Press Start <span class="ml-2">&rarr;</span>
                </a>
                <a href="#courses" class="inline-flex items-center px-8 py-4 bg-slate-800/80 backdrop-blur-sm text-gray-300 font-bold text-lg rounded-xl hover:bg-slate-700 hover:text-white border-2 border-slate-600 hover:border-slate-500 transition-all">
                    View Loot
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Courses Section -->
    <div id="courses" class="py-24 bg-[#0f172a] relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black mb-4 text-white tracking-wide uppercase inline-block relative">
                    Choose Your Class
                    <div class="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
                </h2>
                <p class="text-gray-400 mt-4 text-lg">Select a skill tree to begin your adventure.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- C++ Course Card -->
                <div class="card-hover bg-gray-800 rounded-xl border-2 border-gray-700 p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="text-4xl mb-4 text-blue-400 font-black">C++</div>
                        <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-blue-300 transition-colors">Systems Architect</h3>
                        <p class="text-gray-400 mb-6 leading-relaxed">Master the fundamentals of C++ and object-oriented programming. Build high-performance engines.</p>
                        <a href="/courses/cpp" class="inline-flex items-center text-blue-400 font-bold hover:text-blue-300 tracking-wide uppercase text-sm">
                            Equip Skill <span class="ml-2 text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- PHP Course Card -->
                <div class="card-hover bg-gray-800 rounded-xl border-2 border-gray-700 p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8 8 8z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="text-4xl mb-4 text-indigo-400 font-black">PHP</div>
                        <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-indigo-300 transition-colors">Backend Mage</h3>
                        <p class="text-gray-400 mb-6 leading-relaxed">Learn to build dynamic web applications with the latest PHP features. Control the server-side.</p>
                        <a href="/courses/php" class="inline-flex items-center text-indigo-400 font-bold hover:text-indigo-300 tracking-wide uppercase text-sm">
                            Equip Skill <span class="ml-2 text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- JavaScript Course Card -->
                <div class="card-hover bg-gray-800 rounded-xl border-2 border-gray-700 p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3zm4.5 13.5h3v-9h-3v9zm6 0h3v-9h-3v9z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="text-4xl mb-4 text-yellow-400 font-black">JS</div>
                        <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-yellow-300 transition-colors">Frontend Ninja</h3>
                        <p class="text-gray-400 mb-6 leading-relaxed">From beginner to advanced, cover all aspects of client-side scripting. Master the DOM.</p>
                        <a href="/courses/javascript" class="inline-flex items-center text-yellow-400 font-bold hover:text-yellow-300 tracking-wide uppercase text-sm">
                            Equip Skill <span class="ml-2 text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leaderboard Preview Section -->
    <div class="py-20 bg-[#131b2e] border-t border-gray-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <!-- Description Column -->
                <div class="text-center md:text-left">
                    <span class="text-indigo-400 font-mono text-sm tracking-widest uppercase mb-2 block">PvP Zone</span>
                    <h2 class="text-4xl font-bold mb-6 text-white">Climb the Ranks</h2>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        See how you stack up against other learners. Earn points by completing courses and challenges, and claim your spot on the Global Leaderboard.
                    </p>
                    <!-- Redirects to Login page to force authentication -->
                    <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold text-lg px-8 py-3 rounded-lg hover:from-green-400 hover:to-emerald-500 shadow-lg transform hover:-translate-y-1 transition-all">
                        View Full Rankings
                    </a>
                </div>

                <!-- Leaderboard Column -->
                <div class="w-full">
                    <div class="max-w-md mx-auto bg-gray-800 rounded-xl shadow-2xl border border-gray-700 overflow-hidden relative">
                        <!-- Header -->
                        <div class="bg-gray-900 p-4 border-b border-gray-700 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider">Top Players</h3>
                            <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                        </div>

                        <!-- List -->
                        <div class="p-2">
                            {{-- Dynamic Leaderboard Logic --}}
                            @isset($topLearners)
                                @forelse ($topLearners as $learner)
                                    <div class="flex items-center justify-between p-3 mb-1 rounded-lg {{ $loop->iteration <= 3 ? 'bg-gray-700/50 border border-gray-600' : 'bg-transparent' }}">
                                        <div class="flex items-center gap-4">
                                            <span class="font-mono font-bold text-lg {{ $loop->iteration == 1 ? 'text-yellow-400' : ($loop->iteration == 2 ? 'text-gray-300' : ($loop->iteration == 3 ? 'text-orange-400' : 'text-gray-500')) }}">
                                                #{{ $loop->iteration }}
                                            </span>
                                            <span class="font-bold text-gray-200">{{ $learner->name }}</span>
                                        </div>
                                        <span class="font-mono text-indigo-300">{{ number_format($learner->exptotal) }} XP</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 p-4 text-center">The arena is empty.</p>
                                @endforelse
                            @else
                                <p class="text-gray-500 p-4 text-center">Leaderboard offline.</p>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>