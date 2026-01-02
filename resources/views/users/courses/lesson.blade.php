<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $currentLesson->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Added typography plugin for better text readability -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
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

        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 6rem;
            max-height: calc(100vh - 8rem);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #475569 #1e293b;
        }

        /* Custom Scrollbar */
        .sticky-sidebar::-webkit-scrollbar { width: 6px; }
        .sticky-sidebar::-webkit-scrollbar-track { background: #1e293b; }
        .sticky-sidebar::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 20px; }

        /* HUD Panel Styles */
        .hud-panel {
            background-color: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 0 20px rgba(99, 102, 241, 0.05);
        }

        /* Progress Nodes */
        .progress-node {
            width: 2.5rem; height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-family: 'Courier New', monospace;
            border: 2px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .progress-line { height: 3px; flex-grow: 1; z-index: 5; margin: 0 4px; border-radius: 2px; }

        /* Node States */
        .node-completed { background: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5); border-color: #34d399; }
        .line-completed { background: #059669; }

        .node-active { background: #6366f1; box-shadow: 0 0 15px rgba(99, 102, 241, 0.6); border-color: #a5b4fc; transform: scale(1.1); }
        .line-active { background: linear-gradient(90deg, #10b981, #6366f1); }

        .node-unlocked { background: #4338ca; color: #a5b4fc; }
        .line-unlocked { background: #312e81; }

        .node-locked { background: #1e293b; color: #475569; border-color: #334155; }
        .line-locked { background: #1e293b; }

        /* Typography Override for Prose to match Dark Theme */
        .prose strong { color: #e2e8f0; }
        .prose code { color: #f472b6; background: #2e1065; padding: 2px 4px; rounded: 4px; }
        .prose h1, .prose h2, .prose h3 { color: #f8fafc; margin-top: 1.5em; margin-bottom: 0.5em; }
        .prose p { color: #cbd5e1; line-height: 1.8; }
        .prose ul > li::marker { color: #818cf8; }
        .prose a { color: #818cf8; text-decoration: none; border-bottom: 1px dashed #818cf8; }
        .prose a:hover { color: #a5b4fc; border-style: solid; }
    </style>
</head>
<body class="antialiased">

    @include('users.partials.user-navbar')

    <!-- Main Content -->
    <main class="pt-24 md:pt-28 pb-16">
        <div class="container mx-auto px-4 max-w-7xl">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('courses.show', $course->name) }}" class="group inline-flex items-center text-slate-400 hover:text-indigo-400 font-bold transition-colors uppercase tracking-wider text-xs">
                    <div class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center mr-3 border border-slate-700 group-hover:border-indigo-500 transition-colors">
                        <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    Abort / Return to Map
                </a>
            </div>

            <!-- === NODE PROGRESS BAR === -->
            <div class="w-full hud-panel rounded-xl p-6 mb-8 relative overflow-hidden">
                <!-- Decorative scanline -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
                
                <h2 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Sequence Progress // Part {{ $currentLesson->part_number }}
                </h2>
                
                <div class="flex items-center px-2">
                    @foreach($progressLessons as $lesson)
                        @php
                            $status = $lesson->status; 
                            $nodeColor = 'node-locked';
                            $lineColor = 'line-locked';
                            if ($status == 'completed') { $nodeColor = 'node-completed'; $lineColor = 'line-completed'; }
                            elseif ($status == 'active') { $nodeColor = 'node-active'; $lineColor = 'line-active'; }
                            elseif ($status == 'unlocked') { $nodeColor = 'node-unlocked'; $lineColor = 'line-unlocked'; }
                        @endphp

                        <div class="progress-node {{ $nodeColor }}">
                            {{ $lesson->order }} 
                        </div>
                        @if(!$loop->last)
                            <div class="progress-line {{ $lineColor }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- === Left Column: Lesson List === -->
<aside class="w-full lg:w-1/4">
    <div class="sticky-sidebar hud-panel rounded-xl p-0 overflow-hidden">
        <div class="p-4 border-b border-slate-700 bg-slate-800/50">
            <h3 class="text-xs font-black text-white uppercase tracking-wider">{{ $course->name }} <span class="text-indigo-400">Database</span></h3>
        </div>
        
        <nav class="p-4">
            @foreach($sidebarLessons->groupBy('part_number') as $partNumber => $lessonsInPart)
                <div class="mb-6 last:mb-0">
                    <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 border-l-2 border-slate-600 pl-2">Part {{ $partNumber }}</h4>
                    <ul class="space-y-1">
                        @foreach($lessonsInPart as $lesson)
                            @php
                                $status = $lesson->status;
                            @endphp
                            <li>
                                <a href="{{ route('lessons.show', ['course' => $course->name, 'lesson' => $lesson->id]) }}" 
                                   class="flex items-center p-2 rounded-lg transition-all duration-200 group
                                          @if($status == 'active') 
                                              bg-indigo-600/20 border border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.2)]
                                          @elseif($status == 'completed') 
                                              text-emerald-400 hover:bg-slate-800
                                          @elseif($status == 'unlocked') 
                                              text-slate-300 hover:bg-slate-800 hover:text-white
                                          @else 
                                              text-slate-600 cursor-not-allowed 
                                          @endif">
                                    
                                    <!-- Icon -->
                                    <span class="mr-3 transition-transform group-hover:scale-110 flex-shrink-0">
                                        @if($status == 'active')
                                            <div class="w-4 h-4 rounded-full border-2 border-indigo-400 flex items-center justify-center">
                                                <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></div>
                                            </div>
                                        @elseif($status == 'completed')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($status == 'locked')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        @else
                                            <div class="w-4 h-4 rounded-full border-2 border-slate-600"></div>
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium truncate">{{ $lesson->title }}</span>
                                    <span class="text-xs text-slate-500 ml-2">({{ $lesson->lesson_number }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>
</aside>

                <!-- Right Column: Lesson Content & Activity -->
                <div class="w-full lg:w-3/4"> 
                    <div class="hud-panel rounded-xl p-8 md:p-10 relative">
                        
                        <!-- Header -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-slate-700/50 pb-6">
                            <div>
                                <span class="text-xs font-mono text-indigo-400 bg-indigo-900/30 px-2 py-1 rounded mb-2 inline-block uppercase tracking-wider">Lesson_ID: {{ str_pad($currentLesson->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight uppercase">{{ $currentLesson->title }}</h1>
                            </div>
                            <div class="flex items-center space-x-2 text-slate-400 text-sm font-mono bg-slate-900/50 px-4 py-2 rounded-lg border border-slate-700">
                                <span>SEQ:</span>
                                <span class="text-white">{{ $currentLesson->global_order }}</span>
                                <span class="text-slate-600">/</span>
                                <span class="text-slate-500">{{ $sidebarLessons->count() }}</span>
                            </div>
                        </div>

                        <!-- Lesson Content (Enhanced Readability) -->
                        <div class="prose prose-invert prose-lg max-w-none mb-12">
                            {!! $currentLesson->content !!}
                        </div>

                        <!-- Dynamic Activity Section -->
                        <div class="mt-12">
                            <div class="flex items-center mb-4">
                                <div class="h-px bg-slate-700 flex-grow"></div>
                                <span class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Interactive Module</span>
                                <div class="h-px bg-slate-700 flex-grow"></div>
                            </div>
                            
                            @switch($currentLesson->activity_type)
                                @case('fill_in_the_blank')
                                    @include('users.courses.activities._fill_in_the_blank', [
                                        'data' => $activityData,
                                        'currentLesson' => $currentLesson
                                    ])
                                    @break

                                @case('sandbox')
                                    @include('users.courses.activities._sandbox', [
                                        'data' => $activityData,
                                        'course' => $course
                                    ])
                                    @break

                                @default
                                    @include('users.courses.activities._text_only', [
                                        'currentLesson' => $currentLesson
                                    ])
                            @endswitch
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    

    <!-- COURSE COMPLETED POPUP -->
    @if(session('course_completed'))
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-md">
            <div class="relative bg-[#1e293b] rounded-2xl p-10 max-w-lg w-full text-center shadow-2xl border-4 border-yellow-400 overflow-hidden transform scale-100 animate-bounce-in">
                <!-- Confetti/Rays Background -->
                <div class="absolute inset-0 opacity-20 pointer-events-none" 
                     style="background-image: radial-gradient(circle, #fbbf24 2px, transparent 2.5px); background-size: 24px 24px;">
                </div>
                
                <div class="relative z-10">
                    <div class="mx-auto w-24 h-24 bg-yellow-400 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-yellow-400/50 animate-pulse">
                        <svg class="w-12 h-12 text-black" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    
                    <h2 class="text-4xl font-black text-white mb-2 tracking-tight">COURSE COMPLETED!</h2>
                    <p class="text-indigo-300 text-lg mb-8 font-medium">You've mastered the <span class="text-yellow-400">{{ $course->name }}</span> curriculum!</p>
                    
                    <div class="space-y-4">
                        <a href="{{ route('courses.index') }}" class="block w-full py-4 px-6 bg-yellow-400 hover:bg-yellow-300 text-black font-black text-xl rounded-xl shadow-[0_0_20px_rgba(251,191,36,0.6)] transform hover:scale-105 transition-all duration-200 uppercase tracking-wider">
                            HOORAY!
                        </a>
                        <p class="text-slate-500 text-xs">Click to find your next challenge</p>
                    </div>
                </div>
            </div>
        </div>
        <style>
            @keyframes bounceIn {
                0% { opacity: 0; transform: scale(0.3); }
                50% { opacity: 1; transform: scale(1.05); }
                70% { transform: scale(0.9); }
                100% { transform: scale(1); }
            }
            .animate-bounce-in {
                animation: bounceIn 0.6s cubic-bezier(0.215, 0.610, 0.355, 1.000) both;
            }
        </style>
    @endif

    @include('users.partials.achievement')

</body>
</html>