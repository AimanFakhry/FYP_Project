<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Global Leaderboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,900&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
            color: #e2e8f0;
            font-family: 'Figtree', sans-serif;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%236366f1" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .hud-panel {
            background-color: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 0 20px rgba(99, 102, 241, 0.05);
        }

        /* Rank Styles - Now Dark Mode Compatible */
        .rank-card {
            transition: all 0.3s ease;
            background-color: rgba(30, 41, 59, 0.6); /* Default dark bg */
            border: 1px solid rgba(255,255,255,0.05);
        }
        .rank-card:hover {
            transform: translateX(10px) scale(1.01);
            background-color: rgba(30, 41, 59, 0.9);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        /* Top 3 Ranks - Dark BG with Colored Borders/Glows */
        .rank-1 { 
            background: linear-gradient(90deg, rgba(251, 191, 36, 0.1) 0%, rgba(30, 41, 59, 0.6) 100%);
            border: 2px solid #fbbf24; 
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.2); 
        }
        .rank-2 { 
            background: linear-gradient(90deg, rgba(156, 163, 175, 0.1) 0%, rgba(30, 41, 59, 0.6) 100%);
            border: 2px solid #9ca3af; 
            box-shadow: 0 0 20px rgba(156, 163, 175, 0.2); 
        }
        .rank-3 { 
            background: linear-gradient(90deg, rgba(251, 146, 60, 0.1) 0%, rgba(30, 41, 59, 0.6) 100%);
            border: 2px solid #fb923c; 
            box-shadow: 0 0 20px rgba(251, 146, 60, 0.2); 
        }
        
        /* Highlight current user */
        .rank-me { 
            border: 2px solid #6366f1; 
            background-color: rgba(99, 102, 241, 0.15); 
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); 
        }

        /* Typography */
        .text-glow { text-shadow: 0 0 10px rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    @include('users.partials.user-navbar')

    <!-- Main Content -->
    <main class="pt-28 pb-16 flex-grow">
        <div class="container mx-auto px-4 max-w-4xl">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight uppercase">
                    Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">Leaderboard</span>
                </h1>
                <p class="text-lg text-slate-400">Compete with other players and rise to the top.</p>
            </div>

            <!-- Current User Stats Bar -->
            <div class="hud-panel rounded-xl p-6 mb-10 flex items-center justify-between border-l-4 border-indigo-500">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-900 border-2 border-indigo-500 flex items-center justify-center text-indigo-300 font-bold text-2xl">
                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $currentUser->name }}</h2>
                        <p class="text-sm text-indigo-300">Your Rank</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-black text-white">#{{ $userRank }}</p>
                    <p class="text-sm text-slate-400">{{ number_format($currentUser->exptotal) }} XP</p>
                </div>
            </div>

            <!-- Leaderboard List -->
            <div class="space-y-4">
                <div class="flex justify-between px-6 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                    <span>Rank / Agent</span>
                    <span>Experience Score</span>
                </div>

                @foreach ($rankings as $index => $user)
                    @php
                        // Calculate actual rank based on pagination
                        $rank = $rankings->firstItem() + $index;
                        
                        $rankClass = 'rank-card';
                        if ($rank === 1) $rankClass = 'rank-1';
                        elseif ($rank === 2) $rankClass = 'rank-2';
                        elseif ($rank === 3) $rankClass = 'rank-3';
                        
                        // Check if this row is the logged-in user
                        $isMe = ($user->id === $currentUser->id);
                        if ($isMe) $rankClass .= ' rank-me';
                    @endphp

                    <div class="flex items-center p-4 rounded-xl {{ $rankClass }}">
                        <!-- Rank Number -->
                        <div class="w-16 text-center flex-shrink-0">
                            @if($rank <= 3)
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-black text-lg shadow-lg
                                    {{ $rank == 1 ? 'bg-yellow-400 text-yellow-900' : '' }}
                                    {{ $rank == 2 ? 'bg-gray-300 text-gray-800' : '' }}
                                    {{ $rank == 3 ? 'bg-orange-400 text-orange-900' : '' }}">
                                    {{ $rank }}
                                </div>
                            @else
                                <span class="font-mono text-slate-500 text-xl font-bold">#{{ $rank }}</span>
                            @endif
                        </div>
                        
                        <!-- Avatar & Name -->
                        <div class="flex items-center gap-4 flex-grow pl-4">
                            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white overflow-hidden border border-white/10 text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="font-bold text-lg text-white tracking-wide truncate">
                                {{ $user->name }}
                                @if($isMe) <span class="ml-2 text-[10px] uppercase bg-indigo-600 text-white px-2 py-0.5 rounded-full tracking-wider">You</span> @endif
                            </div>
                        </div>

                        <!-- XP (Uniform styling for all ranks) -->
                        <div class="text-right flex-shrink-0 w-32 pr-2">
                            <!-- Removed conditional styling: now all ranks use the same indigo/slate styling -->
                            <span class="block font-mono font-bold text-xl text-indigo-400">{{ number_format($user->exptotal) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $rankings->links() }}
            </div>

        </div>
    </main>

</body>
</html>