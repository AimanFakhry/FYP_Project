<!-- User Navbar (Gamified HUD) -->
<nav class="fixed w-full top-0 z-50 bg-[#1a1c29] border-b-2 border-indigo-900/50 shadow-2xl backdrop-blur-md bg-opacity-90">
    <!-- Grid Texture Overlay -->
    <div class="absolute inset-0 opacity-5 pointer-events-none" 
         style="background-image: linear-gradient(0deg, transparent 24%, rgba(99, 102, 241, .3) 25%, rgba(99, 102, 241, .3) 26%, transparent 27%, transparent 74%, rgba(99, 102, 241, .3) 75%, rgba(99, 102, 241, .3) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(99, 102, 241, .3) 25%, rgba(99, 102, 241, .3) 26%, transparent 27%, transparent 74%, rgba(99, 102, 241, .3) 75%, rgba(99, 102, 241, .3) 76%, transparent 77%, transparent); background-size: 50px 50px;">
    </div>

    <div class="container mx-auto px-6 py-3 relative z-10">
        <div class="flex items-center justify-between">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                    <div class="h-8 w-8 bg-indigo-600 rounded flex items-center justify-center border border-indigo-400 shadow-[0_0_10px_rgba(99,102,241,0.5)] group-hover:rotate-12 transition-transform duration-300">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-widest text-white uppercase" style="font-family: 'Courier New', monospace; text-shadow: 0 0 10px rgba(99,102,241,0.5);">
                        LEARN<span class="text-indigo-400">CODE</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Navigation Links -->
                <a href="{{ route('users.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ request()->routeIs('users.dashboard') ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-500/50 shadow-[0_0_10px_rgba(99,102,241,0.2)]' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    BASE
                </a>
                <a href="{{ route('courses.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-500/50 shadow-[0_0_10px_rgba(99,102,241,0.2)]' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    MISSIONS
                </a>
                
                @php
                    $dailyCompleted = Auth::user()->last_daily_at && Auth::user()->last_daily_at->isToday();
                @endphp

                @if($dailyCompleted)
                    <!-- Standard Daily Link (Completed) -->
                    <a href="{{ route('daily.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ request()->routeIs('daily.*') ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-500/50 shadow-[0_0_10px_rgba(99,102,241,0.2)]' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        DAILY
                    </a>
                @else
                    <!-- Glowing Daily Link (Not Completed) -->
                    <a href="{{ route('daily.index') }}" class="relative group px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ request()->routeIs('daily.*') ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-500/50' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span class="absolute inset-0 bg-yellow-400 rounded-lg opacity-10 group-hover:opacity-20 animate-pulse pointer-events-none"></span>
                        <span class="relative flex items-center gap-2 {{ request()->routeIs('daily.*') ? 'text-yellow-400' : 'text-yellow-500 group-hover:text-yellow-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            DAILY
                        </span>
                        <!-- Notification Dot -->
                        <span class="absolute top-1 right-1 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    </a>
                @endif

                <a href="{{ route('ranking.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ request()->routeIs('ranking.*') ? 'bg-indigo-900/50 text-indigo-300 border border-indigo-500/50 shadow-[0_0_10px_rgba(99,102,241,0.2)]' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    LEADERBOARD
                </a>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-700 mx-4"></div>

                <!-- User Profile Dropdown -->
                @auth
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden lg:block">
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Player</p>
                                <p class="text-sm font-bold text-white group-hover:text-indigo-200">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="relative z-10 block h-10 w-10 rounded-full overflow-hidden border-2 border-gray-600 group-hover:border-indigo-500 transition-colors shadow-[0_0_10px_rgba(0,0,0,0.5)]">
                                <!-- Profile Picture Placeholder -->
                                <div class="h-full w-full bg-indigo-900 flex items-center justify-center text-indigo-200 font-bold text-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" class="hidden absolute right-0 mt-3 w-56 bg-[#1e293b] rounded-xl shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-gray-700 overflow-hidden z-20 transform origin-top-right transition-all">
                            <div class="px-4 py-3 border-b border-gray-700 bg-gray-800/50">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Status: Online</p>
                                <div class="w-full bg-gray-700 h-1.5 mt-2 rounded-full overflow-hidden">
                                    <div class="bg-green-500 h-full w-full animate-pulse"></div>
                                </div>
                            </div>
                            
                            <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-300 hover:bg-indigo-600 hover:text-white transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Character Profile
                            </a>
                            
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-900/30 hover:text-red-300 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-indigo-400 hover:text-white focus:outline-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('user-menu-dropdown');

        if (menuButton && dropdown) {
            // Toggle dropdown on click
            menuButton.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent click from closing immediately
                dropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking anywhere else on the page
            document.addEventListener('click', function (e) {
                if (!menuButton.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>