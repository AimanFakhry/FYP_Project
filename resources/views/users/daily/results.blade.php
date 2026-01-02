<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mission Report</title>
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

        .text-glow {
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .result-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }
        .result-card.correct {
            border-color: #059669; /* Green-700 */
            box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.1);
        }
        .result-card.incorrect {
            border-color: #991b1b; /* Red-800 */
            box-shadow: inset 0 0 20px rgba(239, 68, 68, 0.1);
        }
    </style>
</head>
<body class="antialiased">

    @include('users.partials.user-navbar')

    <main class="pt-28 pb-16">
        <div class="container mx-auto px-4 max-w-3xl">
            
            <div class="hud-panel rounded-xl overflow-hidden shadow-2xl relative">
                <!-- Decorative Top Bar -->
                <div class="h-2 w-full bg-gradient-to-r {{ $score == $total ? 'from-green-500 via-emerald-400 to-green-500' : 'from-indigo-600 via-purple-500 to-indigo-600' }}"></div>

                <!-- Header -->
                <div class="p-10 text-center bg-slate-800/80 border-b border-slate-700 relative overflow-hidden">
                    <!-- Background Glow -->
                     <div class="absolute inset-0 opacity-20 pointer-events-none" 
                         style="background-image: radial-gradient(circle at center, {{ $score == $total ? '#10b981' : '#6366f1' }} 0%, transparent 70%);">
                    </div>

                    <p class="uppercase tracking-[0.3em] text-xs font-bold text-slate-400 mb-4 relative z-10">Mission Debrief</p>
                    
                    <div class="relative z-10 inline-block">
                        <h1 class="text-7xl font-black mb-2 text-white text-glow tracking-tighter">
                            {{ $score }}<span class="text-3xl text-slate-500">/{{ $total }}</span>
                        </h1>
                        <div class="h-1 w-full bg-slate-700 rounded-full mt-2 overflow-hidden">
                            <div class="h-full {{ $score == $total ? 'bg-green-500' : 'bg-indigo-500' }}" style="width: {{ ($score/$total)*100 }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6 relative z-10">
                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 font-mono font-bold">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            XP Gained: +{{ $xpEarned }}
                        </span>
                    </div>
                </div>

                <div class="p-8 bg-[#0b0e14]">
                    <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-6 border-b border-slate-800 pb-2">Analysis Log</h2>
                    
                    <div class="space-y-6">
                        @foreach($results as $index => $result)
                            <div class="result-card p-5 rounded-lg {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-xs font-mono text-slate-500">QUERY_ID: #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if($result['is_correct'])
                                        <span class="text-xs font-bold text-green-400 uppercase tracking-wider flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Success
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-red-400 uppercase tracking-wider flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Failed
                                        </span>
                                    @endif
                                </div>

                                <p class="font-mono text-slate-300 text-base mb-4 leading-relaxed bg-black/30 p-3 rounded border border-slate-700/50">
                                    {!! str_replace('____', '<span class="text-indigo-400 border-b border-indigo-500/50 px-1">____</span>', $result['question']->question) !!}
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm font-mono">
                                    <div class="bg-black/20 p-3 rounded border border-slate-700/50">
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">User Input</span>
                                        <span class="{{ $result['is_correct'] ? 'text-green-400' : 'text-red-400 line-through decoration-red-500/50' }}">
                                            {{ $result['user_answer'] ?: '(NULL)' }}
                                        </span>
                                    </div>
                                    @if(!$result['is_correct'])
                                        <div class="bg-black/20 p-3 rounded border border-slate-700/50">
                                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Expected Output</span>
                                            <span class="text-green-400">
                                                {{ $result['correct_answer'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 text-center">
                        <a href="{{ route('users.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold uppercase tracking-widest rounded-lg transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)] hover:shadow-[0_0_30px_rgba(79,70,229,0.5)] border border-indigo-500 group">
                            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Return to Base
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>