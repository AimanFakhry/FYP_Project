<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,900&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
            color: #e2e8f0;
            font-family: 'Figtree', sans-serif;
            background-image: 
                linear-gradient(rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.95)),
                url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%236366f1" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
        
        .hud-panel {
            background-color: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 0 20px rgba(99, 102, 241, 0.05);
        }

        .input-hud {
            background-color: #1e293b;
            border: 2px solid #334155;
            color: #e2e8f0;
            font-family: 'Courier New', monospace;
            transition: all 0.3s ease;
        }
        .input-hud:focus {
            background-color: #0f172a;
            border-color: #fbbf24; /* Yellow focus for daily challenge */
            box-shadow: 0 0 10px rgba(251, 191, 36, 0.3);
            outline: none;
        }
    </style>
</head>
<body class="antialiased bg-gray-100">

    @include('users.partials.user-navbar')

    <main class="pt-28 pb-16">
        <div class="container mx-auto px-4 max-w-3xl">
            
            <div class="hud-panel rounded-xl overflow-hidden shadow-2xl relative">
                <!-- Decorative Top Bar -->
                <div class="h-2 w-full bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500"></div>

                <!-- Header -->
                <div class="p-8 text-center bg-slate-800/50 border-b border-slate-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-500/20 border-2 border-yellow-500 text-yellow-400 mb-4 {{ isset($alreadyCompleted) && $alreadyCompleted ? '' : 'animate-pulse' }}">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h1 class="text-3xl font-black uppercase tracking-widest text-white mb-2 text-glow">Daily Protocol</h1>
                    <p class="text-slate-400 text-sm">Complete 5 challenges to earn bonus XP.</p>
                </div>

                @if(isset($notEligible) && $notEligible)
                    <!-- Not Eligible View -->
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-red-500/10 border border-red-500/50 mb-6 shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                            <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2 uppercase tracking-wide">Access Denied</h2>
                        <p class="text-slate-400 mb-6">You need more field experience to attempt the Daily Protocol.</p>
                        
                        <div class="bg-slate-900/50 p-5 rounded-lg border border-slate-700 inline-block mb-8 text-left min-w-[280px]">
                             <p class="text-indigo-400 text-xs font-mono uppercase tracking-widest mb-2 border-b border-slate-700 pb-1">Requirement</p>
                             <p class="text-white font-bold text-lg mb-1">Complete Fill-in-the-Blanks</p>
                             <div class="w-full bg-slate-700 h-2 rounded-full overflow-hidden mb-1">
                                 <div class="bg-red-500 h-full" style="width: {{ ($completedCount / $requiredCount) * 100 }}%"></div>
                             </div>
                             <p class="text-xs text-slate-500 font-mono text-right">Progress: {{ $completedCount }} / {{ $requiredCount }}</p>
                        </div>
                        
                        <div>
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/50 border border-indigo-500 group">
                                Return to Courses <span class="ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @elseif(isset($alreadyCompleted) && $alreadyCompleted)
                    <!-- Already Completed View -->
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-green-500/10 border border-green-500/50 mb-6">
                            <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Mission Accomplished</h2>
                        <p class="text-slate-400 mb-8">You have already completed your daily challenge for today.<br>Come back tomorrow for more!</p>
                        
                        <a href="{{ route('users.dashboard') }}" class="inline-block px-8 py-3 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 transition-colors border border-slate-600">
                            Return to Base
                        </a>
                    </div>
                @else
                    <!-- Form -->
                    <form action="{{ route('daily.check') }}" method="POST" class="p-8">
                        @csrf
                        
                        @if($questions->isEmpty())
                            <div class="text-center py-12 text-slate-500 border-2 border-dashed border-slate-700 rounded-lg">
                                <p>No questions available right now. Check back later!</p>
                                <a href="{{ route('users.dashboard') }}" class="mt-4 inline-block text-indigo-400 font-bold hover:text-indigo-300">Return to Base</a>
                            </div>
                        @else
                            <div class="space-y-8">
                                @foreach($questions as $index => $q)
                                    <div class="relative pl-8 border-l-2 border-slate-700 hover:border-yellow-500 transition-colors duration-300 group">
                                        <!-- Number Indicator -->
                                        <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-slate-900 border-2 border-slate-600 group-hover:border-yellow-500 flex items-center justify-center text-xs font-bold text-slate-400 group-hover:text-yellow-400 transition-all">
                                            {{ $index + 1 }}
                                        </div>

                                        <div class="mb-3">
                                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider bg-indigo-900/30 px-2 py-1 rounded">
                                                {{ $q->lesson->lessonGroup->course->name ?? 'General' }}
                                            </span>
                                            <h3 class="text-lg font-bold text-gray-200 mt-2 font-mono leading-relaxed">
                                                {{-- Display question with HUD style blank line --}}
                                                {!! str_replace('____', '<span class="inline-block w-16 border-b-2 border-dashed border-yellow-500 mx-1 animate-pulse"></span>', $q->question) !!}
                                            </h3>
                                        </div>
                                        
                                        <input type="hidden" name="question_ids[]" value="{{ $q->id }}">
                                        <input type="text" 
                                               name="answers[]" 
                                               class="input-hud w-full p-4 rounded-lg"
                                               placeholder="Enter solution..."
                                               autocomplete="off">
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12 text-center">
                                <button type="submit" class="inline-flex items-center justify-center w-full md:w-auto px-10 py-4 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-lg uppercase tracking-widest rounded-lg transition-all transform hover:scale-[1.02] shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                                    Submit Answers
                                </button>
                            </div>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </main>

</body>
</html>