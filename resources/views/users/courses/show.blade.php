<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learning Path - {{ $course->name }}</title>
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
                url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%236366f1" fill-opacity="0.05"%3E%3Cpath d="M30 10L0 30L30 50L60 30zM30 0L0 20L30 40L60 20z" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
        
        .path-container {
            position: relative;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 4rem 0;
        }
        
        .part-group {
            position: relative;
            width: 100%;
            padding: 2rem 0;
            margin-bottom: 4rem;
            min-height: {{ 3 * 150 }}px; 
        }

        /* Connecting Lines (Dashed Neon) */
        .svg-path-line {
            stroke: rgba(99, 102, 241, 0.4);
            stroke-width: 3px;
            stroke-dasharray: 10 10;
            animation: dash 30s linear infinite;
            filter: drop-shadow(0 0 2px rgba(99, 102, 241, 0.5));
        }

        @keyframes dash {
            from { stroke-dashoffset: 0; }
            to { stroke-dashoffset: -1000; }
        }

        /* HUD Panel Styles for Part Header */
        .part-header {
            position: relative;
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(99, 102, 241, 0.3);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.1);
            border-radius: 4px;
            clip-path: polygon(10% 0, 100% 0, 100% 100%, 0% 100%, 0% 40%);
        }
        .part-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: #6366f1;
        }

        /* Node Styles (Updated CSS only) */
        .lesson-row {
            position: relative;
            z-index: 10;
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
            transition: all 0.3s ease;
        }
        
        .lesson-node {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            text-align: center;
            border: 4px solid;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            background-color: #1e293b; /* Default dark bg */
        }
        .lesson-node:hover {
            transform: scale(1.15) translateY(-5px);
            box-shadow: 0 0 35px rgba(99, 102, 241, 0.6);
            z-index: 20;
        }

        /* Status Colors */
        .lesson-node.completed {
            background: radial-gradient(circle at 30% 30%, #34d399, #059669);
            border-color: #10b981;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .lesson-node.unlocked {
            background: radial-gradient(circle at 30% 30%, #818cf8, #4f46e5);
            border-color: #6366f1;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
            animation: pulse-glow 2s infinite;
        }
        .lesson-node.locked {
            background-color: #1e293b;
            border-color: #334155;
            color: #475569;
            cursor: not-allowed;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.5); }
            50% { box-shadow: 0 0 30px rgba(99, 102, 241, 0.8); border-color: #a5b4fc; }
        }

        /* Tooltip styles */
        .lesson-node .tooltip-text {
            visibility: hidden;
            width: 180px;
            background-color: rgba(15, 23, 42, 0.95);
            color: #e2e8f0;
            text-align: center;
            border: 1px solid #6366f1;
            border-radius: 4px;
            padding: 10px;
            position: absolute;
            z-index: 30;
            bottom: 130%; 
            left: 50%;
            margin-left: -90px; 
            opacity: 0;
            transition: opacity 0.2s, transform 0.2s;
            transform: translateY(10px);
            font-size: 0.875rem;
            pointer-events: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }
        .lesson-node .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -6px;
            border-width: 6px;
            border-style: solid;
            border-color: #6366f1 transparent transparent transparent;
        }
        .lesson-node:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="antialiased">

    @include('users.partials.user-navbar')

    <main class="pt-28 pb-16 min-h-screen">
        <div class="container mx-auto px-4 max-w-5xl">

            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('courses.index') }}" class="group inline-flex items-center text-slate-400 hover:text-indigo-400 font-bold transition-colors uppercase tracking-wider text-sm">
                    <div class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center mr-3 border border-slate-700 group-hover:border-indigo-500 transition-colors">
                        <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    Mission Select
                </a>
            </div>

            <!-- Course Header HUD -->
            <div class="hud-panel relative bg-slate-800/50 backdrop-blur-md rounded-xl p-8 mb-12 border border-slate-700 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="relative z-10">
                    <span class="inline-block py-1 px-2 rounded bg-indigo-900/50 border border-indigo-500/50 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-2">Active Campaign</span>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight uppercase">{{ $course->name }}</h1>
                    <p class="text-lg text-slate-400 max-w-2xl border-l-2 border-indigo-500 pl-4">Sector map loaded. Proceed to the next checkpoint to continue your training.</p>
                </div>
            </div>
            
            <div class="path-container" id="path-container">
                
                @foreach ($lessonGroups as $group)
                    
                    <!-- Part Header (HUD Style) -->
                    <div class="w-full max-w-md mx-auto my-12">
                        <div class="part-header py-3 px-6 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-white uppercase tracking-wider">
                                {{ $group->title }}
                            </h2>
                            <span class="text-xs font-mono text-indigo-400 bg-indigo-900/30 px-2 py-1 rounded">SEC-0{{ $group->order }}</span>
                        </div>
                    </div>
                    
                    <div class="part-group" data-part-id="{{ $group->id }}">
                        <svg class="part-svg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; pointer-events: none;"></svg>
                        
                        @foreach ($group->lessons as $index => $lesson)
                            @php
                                $nodeClass = $lesson->status; 
                                
                                $hash = crc32($course->id . '-' . $lesson->id . '-' . $lesson->title);
                                $randomOffset = ($hash % 100) - 50; 
                                
                                if ($index % 2 == 0) {
                                     $style = "transform: translateX(-" . (abs($randomOffset) + 50) . "px);";
                                } else {
                                     $style = "transform: translateX(" . (abs($randomOffset) + 50) . "px);";
                                }

                                $url = ($lesson->status !== 'locked') ? route('lessons.show', ['course' => $course->name, 'lesson' => $lesson->id]) : '#';
                            @endphp

                            <div class="lesson-row" style="{{ $style }}">
                                <a href="{{ $url }}" 
                                   class="lesson-node {{ $nodeClass }}"
                                   @if($lesson->status === 'locked') 
                                       onclick="return false;" 
                                   @endif>
                                    
                                    <span class="text-3xl font-mono">{{ $lesson->order }}</span>
                                    
                                    <div class="tooltip-text">
                                        <p class="font-bold text-indigo-300 mb-1 uppercase text-xs tracking-widest">Mission Info</p>
                                        <p class="font-bold text-white text-base mb-1">{{ $lesson->title }}</p>
                                        <p class="text-xs {{ $lesson->status == 'completed' ? 'text-green-400' : ($lesson->status == 'unlocked' ? 'text-indigo-400' : 'text-slate-500') }} uppercase font-bold tracking-wider">
                                            Status: {{ $lesson->status }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- UNTOUCHED JAVASCRIPT LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const partGroups = document.querySelectorAll('.part-group');

            partGroups.forEach(group => {
                const svgContainer = group.querySelector('.part-svg');
                const nodes = group.querySelectorAll('.lesson-node');

                if (nodes.length < 2 || !svgContainer) return;

                const containerRect = group.getBoundingClientRect();

                for (let i = 0; i < nodes.length - 1; i++) {
                    const startNode = nodes[i];
                    const endNode = nodes[i + 1];

                    const startRect = startNode.getBoundingClientRect();
                    const endRect = endNode.getBoundingClientRect();

                    const x1 = (startRect.left + startRect.width / 2) - containerRect.left;
                    const y1 = (startRect.top + startRect.height / 2) - containerRect.top;
                    const x2 = (endRect.left + endRect.width / 2) - containerRect.left;
                    const y2 = (endRect.top + endRect.height / 2) - containerRect.top;

                    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', x1);
                    line.setAttribute('y1', y1);
                    line.setAttribute('x2', x2);
                    line.setAttribute('y2', y2);
                    line.setAttribute('class', 'svg-path-line');

                    svgContainer.appendChild(line);
                }
            });
        });
    </script>

</body>
</html>