<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }}'s Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* Avatars */
        .avatar-svg { width: 100%; height: 100%; }
        
        /* Themes */
        .theme-cheerful { --bg-color: #f3f4f6; --card-bg: #ffffff; --primary: #4f46e5; --accent: #fbbf24; --text: #1f2937; }
        .theme-spacy { --bg-color: #1a0033; --card-bg: #2d1b4e; --primary: #a78bfa; --accent: #f472b6; --text: #f3f4f6; background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%239C92AC" fill-opacity="0.2"%3E%3Cpath d="M1 1h2v2H1V1zm15 15h2v2h-2v-2z"/%3E%3C/g%3E%3C/svg%3E'); }
        .theme-techy { --bg-color: #0f172a; --card-bg: #1e293b; --primary: #10b981; --accent: #0ea5e9; --text: #e2e8f0; font-family: 'Courier New', Courier, monospace; }

        body { background-color: var(--bg-color); color: var(--text); }
        .themed-card { background-color: var(--card-bg); color: var(--text); }
        .themed-text { color: var(--text); }
        .themed-primary-bg { background-color: var(--primary); }
        .themed-primary-text { color: var(--primary); }
        .themed-accent-text { color: var(--accent); }
    </style>
</head>
<body class="antialiased theme-{{ $user->theme }}">

    @include('users.partials.user-navbar')

    <!-- Main Content -->
    <main class="pt-24 md:pt-28 pb-16">
        <div class="container mx-auto px-4 max-w-6xl">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Column 1: Player Card -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Player Card -->
                    <div class="themed-card p-6 rounded-lg shadow-xl text-center border-2 border-transparent {{ $user->theme == 'techy' ? 'border-green-500' : '' }}">
                        <!-- Profile Picture -->
                        <div class="h-32 w-32 rounded-full themed-primary-bg flex items-center justify-center p-2 mx-auto mb-4 border-4 border-white shadow-lg overflow-hidden">
                             @if($user->avatar == 'cat')
                                <svg class="avatar-svg text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-8 0-8s8 8 0 8zm-5-9c-.83 0-1.5-.67-1.5-1.5S6.17 8 7 8s1.5.67 1.5 1.5S7.83 11 7 11zm3.5 3c-.83 0-1.5-.67-1.5-1.5S9.67 11 10.5 11s1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm3.5-3c-.83 0-1.5-.67-1.5-1.5S13.17 8 14 8s1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm3.5 0c-.83 0-1.5-.67-1.5-1.5S16.67 8 17.5 8s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                             @elseif($user->avatar == 'dog')
                                <svg class="avatar-svg text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm-1-11c-.8 0-1.5-.7-1.5-1.5S10.2 6 11 6s1.5.7 1.5 1.5-.7 1.5-1.5 1.5zm3.5 2.5c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5 1.5.7 1.5 1.5-.7 1.5-1.5 1.5z"/></svg>
                             @else
                                <span class="text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                             @endif
                        </div>
                        
                        <h1 class="text-3xl font-bold themed-text">{{ $user->name }}</h1>
                        <p class="text-gray-500 mb-4">{{ $user->email }}</p>
                        
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="inline-block themed-primary-bg text-white px-6 py-2 rounded-full font-bold hover:opacity-90 transition-opacity">
                                Edit Profile
                            </a>
                        </div>
                    </div>

                    <!-- Core Stats -->
                    <div class="themed-card p-6 rounded-lg shadow-xl">
                        <h2 class="text-2xl font-bold themed-text mb-4 text-center">Stats</h2>
                        <div class="grid grid-cols-2 gap-6 text-center">
                            <div>
                                <span class="text-sm font-medium text-gray-500 uppercase">XP</span>
                                <p class="text-4xl font-bold themed-primary-text">{{ number_format($user->exptotal) }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 uppercase">Rank</span>
                                <p class="text-4xl font-bold themed-accent-text">#{{ $leaderboardRank }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Column 2: Progress & Achievements -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Course Progress -->
                    <div class="themed-card p-6 rounded-lg shadow-xl">
                        <h2 class="text-2xl font-bold themed-text mb-6">Course Progress</h2>
                        <div class="space-y-6">
                            @forelse ($courseProgress as $course)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-lg font-medium themed-text">{{ $course['name'] }}</span>
                                        
                                        <div class="flex items-center gap-4">
                                            <span class="text-lg font-medium themed-text">{{ $course['progress'] }}%</span>
                                            
                                            <!-- Reset Button -->
                                            <form action="{{ route('profile.reset_course') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset all progress for {{ $course['name'] }}? This cannot be undone.');">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $course['id'] }}">
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline font-medium" title="Reset progress for this course">
                                                    Reset
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="themed-primary-bg h-4 rounded-full" style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No courses started. <a href="{{ route('courses.index') }}" class="themed-primary-text hover:underline">Start now!</a></p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Achievements -->
                    <div class="themed-card p-6 rounded-lg shadow-xl">
                        <h2 class="text-2xl font-bold themed-text mb-6">Achievements</h2>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                            @foreach ($achievements as $ach)
                                <div class="text-center">
                                    <div class="bg-gray-200 p-3 rounded-full inline-block">
                                        <svg class="h-10 w-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.699-3.177a1 1 0 111.44 1.414l-1.698 3.177L19.5 9a1 1 0 110 2l-3.105 1.68 1.698 3.177a1 1 0 11-1.44 1.414L14.954 14.09 11 15.677V17a1 1 0 11-2 0v-1.323l-3.954-1.582-1.699 3.177a1 1 0 11-1.44-1.414l1.698-3.177L1.5 11a1 1 0 110-2l3.105-1.68-1.698-3.177a1 1 0 111.44-1.414L5.046 5.91 9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <p class="text-xs themed-text mt-1">{{ $ach->name }}</p>
                                </div>
                            @endforeach
                            @if($achievements->isEmpty())
                                <p class="col-span-4 text-gray-500">No achievements yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>