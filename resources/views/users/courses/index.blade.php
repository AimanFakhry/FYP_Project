<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mission Select - Courses</title>
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .hud-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.5), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .hud-panel:hover {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
            transform: translateY(-5px);
        }

        .hud-panel:hover::before {
            transform: translateX(100%);
        }

        .progress-bar {
            background: linear-gradient(90deg, #4f46e5, #818cf8);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    @include('users.partials.user-navbar')

    <!-- Main Content -->
    <main class="flex-grow pt-28 pb-16 flex items-center justify-center">
        <div class="container mx-auto px-4 max-w-6xl">

            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight uppercase">
                    Available <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Missions</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto">Select a skill path to upgrade your abilities and unlock new achievements.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                
                @foreach($courses as $course)
                    @php
                        // Determine accent color based on course name for variety
                        $accentColor = 'indigo';
                        $iconColor = 'text-indigo-500';
                        if (stripos($course->name, 'c++') !== false) {
                            $accentColor = 'blue';
                            $iconColor = 'text-blue-500';
                        } elseif (stripos($course->name, 'javascript') !== false || stripos($course->name, 'js') !== false) {
                            $accentColor = 'yellow';
                            $iconColor = 'text-yellow-500';
                        }
                    @endphp

                    <a href="{{ route('courses.show', ['course' => $course->name]) }}" class="hud-panel rounded-2xl flex flex-col h-full group">
                        
                        <!-- Card Header / Icon -->
                        <div class="p-8 pb-0 flex justify-center">
                            <div class="w-24 h-24 rounded-2xl bg-gray-900/50 border border-gray-700 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300 {{ $iconColor }}">
                                {!! $course->icon_svg ?? '<svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>' !!}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-8 text-center flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-3xl font-black text-white mb-4 group-hover:text-{{ $accentColor }}-400 transition-colors">{{ $course->name }}</h3>
                                <p class="text-slate-400 mb-6 leading-relaxed">{{ $course->description }}</p>
                            </div>

                            <!-- Progress Section -->
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                                    <span>Progress</span>
                                    <span class="text-white">{{ $progress[strtolower($course->name)] ?? $progress['cpp'] ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-slate-700">
                                    <div class="progress-bar h-full rounded-full relative" style="width: {{ $progress[strtolower($course->name)] ?? $progress['cpp'] ?? 0 }}%">
                                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <span class="inline-block py-2 px-6 rounded-lg bg-gray-800 text-gray-300 font-bold text-sm border border-gray-600 group-hover:bg-{{ $accentColor }}-600 group-hover:text-white group-hover:border-{{ $accentColor }}-500 transition-all duration-300">
                                        INITIALIZE &rarr;
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </main>

</body>
</html>