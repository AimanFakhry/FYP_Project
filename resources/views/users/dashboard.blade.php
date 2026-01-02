<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - YourApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,900&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
            color: #e2e8f0;
            font-family: 'Figtree', sans-serif;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 25%), 
                radial-gradient(circle at 85% 30%, rgba(168, 85, 247, 0.08) 0%, transparent 25%);
        }
        
        /* HUD Panel Style */
        .hud-panel {
            background-color: rgba(30, 41, 59, 0.7); /* Slate 800 with opacity */
            border: 1px solid rgba(148, 163, 184, 0.1);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .hud-panel:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }

        /* Stat Value Glow */
        .stat-glow {
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        /* Progress Bar Animation */
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .progress-shimmer {
            background: linear-gradient(90deg, #4f46e5 25%, #818cf8 50%, #4f46e5 75%);
            background-size: 200% 100%;
            animation: shimmer 3s infinite linear;
        }
    </style>
</head>
<body class="antialiased min-h-screen">

    @include('users.partials.user-navbar')

    <!-- Main Content -->
    <main class="pt-28 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">
            
            <!-- Hero Header -->
            <div class="mb-12 relative">
                <div class="absolute -left-4 top-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                <h1 class="text-4xl md:text-6xl font-black text-white mb-2 tracking-tight">
                    WELCOME BACK, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">{{ strtoupper(Auth::user()->name) }}</span>
                </h1>
                <p class="text-lg text-slate-400 pl-1">System operational. Ready to resume training.</p>
            </div>

            <!-- Stats Bar (HUD) -->
            <div class="mb-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Level Card -->
                <div class="hud-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-20 h-20 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Current Level</span>
                    <p class="text-5xl font-black text-white mt-2 stat-glow">{{ floor(Auth::user()->exptotal / 1000) + 1 }}</p>
                    
                    <!-- XP Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-slate-400 mb-1">
                            <span>Progress</span>
                            <span>{{ Auth::user()->exptotal % 1000 }} / 1000 XP</span>
                        </div>
                        <div class="w-full bg-slate-700 h-2 rounded-full overflow-hidden">
                            <div class="progress-shimmer h-full rounded-full" style="width: {{ ((Auth::user()->exptotal % 1000) / 1000) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Total XP Card -->
                <div class="hud-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-20 h-20 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-yellow-500 uppercase tracking-widest">Total Experience</span>
                    <p class="text-5xl font-black text-white mt-2">{{ number_format(Auth::user()->exptotal) }}</p>
                    <p class="text-sm text-slate-400 mt-2">Rank: <span class="text-white font-bold">#{{ Auth::user()->getLeaderboardRank() }}</span> Global</p>
                </div>

                <!-- Active Quests Card -->
                <div class="hud-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-20 h-20 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Active Missions</span>
                    <p class="text-5xl font-black text-white mt-2">3</p>
                    <p class="text-sm text-slate-400 mt-2">Courses available to start or continue.</p>
                </div>
            </div>

            <!-- "Continue Learning" Section -->
            <div>
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-px bg-slate-700 flex-grow"></div>
                    <h2 class="text-2xl font-bold text-white uppercase tracking-widest">Select Mission</h2>
                    <div class="h-px bg-slate-700 flex-grow"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- C++ Card -->
                    <div class="hud-panel rounded-xl overflow-hidden group hover:border-blue-500/50">
                        <div class="p-8 bg-gradient-to-br from-blue-900/50 to-slate-900 relative">
                            <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-16 h-16 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                            </div>
                            <h3 class="text-3xl font-black text-white mb-1">C++</h3>
                            <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">System Architecture</span>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-400 mb-6 text-sm">Build high-performance applications and powerful software systems.</p>
                            <a href="{{ route('courses.show', 'C++') }}" class="block w-full py-3 text-center rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold transition-all shadow-lg shadow-blue-900/20">
                                ENGAGE &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- PHP Card -->
                    <div class="hud-panel rounded-xl overflow-hidden group hover:border-indigo-500/50">
                        <div class="p-8 bg-gradient-to-br from-indigo-900/50 to-slate-900 relative">
                            <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8 8 8z"/></svg>
                            </div>
                            <h3 class="text-3xl font-black text-white mb-1">PHP</h3>
                            <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Server Backend</span>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-400 mb-6 text-sm">Master server-side web development and database integration.</p>
                            <a href="{{ route('courses.show', 'PHP') }}" class="block w-full py-3 text-center rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition-all shadow-lg shadow-indigo-900/20">
                                ENGAGE &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- JavaScript Card -->
                    <div class="hud-panel rounded-xl overflow-hidden group hover:border-yellow-500/50">
                        <div class="p-8 bg-gradient-to-br from-yellow-900/30 to-slate-900 relative">
                            <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3zm4.5 13.5h3v-9h-3v9zm6 0h3v-9h-3v9z"/></svg>
                            </div>
                            <h3 class="text-3xl font-black text-white mb-1">JS</h3>
                            <span class="text-xs font-bold text-yellow-500 uppercase tracking-wider">Frontend Interactive</span>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-400 mb-6 text-sm">Power the interactive web. Build dynamic interfaces and logic.</p>
                            <a href="{{ route('courses.show', 'JavaScript') }}" class="block w-full py-3 text-center rounded-lg bg-yellow-600 hover:bg-yellow-500 text-white font-bold transition-all shadow-lg shadow-yellow-900/20">
                                ENGAGE &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </main>

</body>
</html>