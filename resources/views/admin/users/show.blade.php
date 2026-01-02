<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>User Details: {{ $user->name }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }</style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb / Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Users
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">User Profile</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Edit User
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                            Delete User
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: User Info & Progress -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Profile Card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                        <div class="bg-gray-50 px-6 py-6 border-b border-gray-200 flex flex-col items-center">
                            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-3xl mb-4">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Joined</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Total XP</span>
                                <span class="text-sm font-medium text-indigo-600">{{ number_format($user->exptotal) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500">Achievements</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $achievements->count() }} Unlocked</span>
                            </div>
                        </div>
                    </div>

                    <!-- Course Progress Stats -->
                    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Course Progress</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            @foreach($courseProgress as $cp)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700">{{ $cp->name }}</span>
                                        <span class="text-gray-500">{{ $cp->completed }} / {{ $cp->total }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $cp->color_class }}" style="width: {{ $cp->percentage }}%"></div>
                                    </div>
                                    <p class="text-right text-xs text-gray-400 mt-1">{{ $cp->percentage }}% Complete</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right Column: History Timeline -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg border border-gray-200 h-full flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Recent Activity History</h3>
                            <span class="text-xs text-gray-500">Last 20 Actions</span>
                        </div>
                        
                        <div class="flex-grow overflow-auto p-6">
                            @if($history->count() > 0)
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        @foreach($history as $lesson)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                            <div>
                                                                <p class="text-sm text-gray-500">
                                                                    Completed lesson <span class="font-medium text-gray-900">{{ $lesson->title }}</span>
                                                                </p>
                                                                <p class="text-xs text-gray-400 mt-0.5">
                                                                    Course: {{ $lesson->lessonGroup->course->name }}
                                                                </p>
                                                            </div>
                                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                                <time datetime="{{ $lesson->pivot->completed_at }}">{{ \Carbon\Carbon::parse($lesson->pivot->completed_at)->diffForHumans() }}</time>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="mt-2 text-sm">No recent activity recorded.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>