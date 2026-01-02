<!-- Guest Navbar (Gamified Theme) -->
<nav class="fixed w-full top-0 z-50 bg-[#1a1c29] border-b-4 border-indigo-600 shadow-2xl">
    <!-- Optional: A subtle grid pattern overlay to give it a tech/game feel -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" 
         style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 20px 20px;">
    </div>

    <div class="container mx-auto px-6 py-4 relative z-10">
        <div class="flex items-center justify-between">
            
            <!-- LEFT SIDE: Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('welcome') }}" class="group flex items-center space-x-2">
                    <!-- Logo Icon (Game Controller-ish shape or Code brackets) -->
                    <div class="h-10 w-10 bg-indigo-600 rounded flex items-center justify-center border-2 border-indigo-400 shadow-[0_0_10px_rgba(99,102,241,0.5)] group-hover:rotate-12 transition-transform duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                        </svg>
                    </div>
                    
                    <!-- Text Logo -->
                    <div class="flex flex-col items-start">
                        <span class="text-2xl font-black tracking-widest text-white uppercase" style="font-family: 'Courier New', monospace; text-shadow: 2px 2px 0px #4f46e5;">
                            LEARN<span class="text-indigo-400">CODE</span>
                        </span>
                        <span class="text-[0.6rem] text-gray-400 uppercase tracking-[0.2em] font-bold bg-gray-800 px-1 rounded">
                            Level Up Coding
                        </span>
                    </div>
                </a>
            </div>

            <!-- RIGHT SIDE: Action Buttons (Login / Sign Up) -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" 
                   class="group relative inline-flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 bg-gray-800 font-mono border-2 border-gray-600 rounded hover:border-indigo-500 hover:bg-gray-700 hover:text-indigo-400 hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">&gt;</span>
                    Log In
                </a>

                <a href="{{ route('register') }}" 
                   class="relative inline-flex items-center justify-center px-6 py-2 font-bold text-white transition-all duration-200 bg-indigo-600 font-mono border-2 border-indigo-400 rounded hover:bg-indigo-700 hover:border-indigo-300 hover:shadow-[0_0_20px_rgba(99,102,241,0.6)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform active:scale-95">
                    Sign Up
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                    </span>
                </a>
            </div>

        </div>
    </div>
</nav>