<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage {{ $course->name }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }</style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex items-center min-w-0">
                    <a href="{{ route('admin.courses.index') }}" class="mr-4 p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">{{ $course->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Course Curriculum</p>
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('admin.groups.create', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add New Group
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="space-y-8">
                @forelse ($course->lessonGroups as $group)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                        
                        <!-- Group Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-gray-300 text-xs font-bold text-gray-500">
                                    {{ $group->order }}
                                </span>
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $group->title }}</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.lessons.create', $group) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-md border border-indigo-100 hover:border-indigo-200 transition-colors">
                                    + Add Lesson
                                </a>
                                <div class="h-4 w-px bg-gray-300 mx-1"></div>
                                <a href="{{ route('admin.groups.edit', $group) }}" class="text-gray-400 hover:text-gray-600 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Delete this group?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Lessons List -->
                        <ul class="divide-y divide-gray-100">
                            @forelse ($group->lessons as $lesson)
                                <li class="px-6 py-4 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                                    <div class="flex items-center min-w-0 gap-4">
                                        <div class="flex-shrink-0 text-xs font-mono text-gray-400 w-6 text-center">
                                            {{ $lesson->order }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $lesson->title }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $lesson->activity_type === 'text_only' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $lesson->activity_type === 'fill_in_the_blank' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $lesson->activity_type === 'sandbox' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $lesson->activity_type)) }}
                                                </span>
                                                @if($lesson->achievement_id)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                        🏆 Award
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Delete this lesson?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                    No lessons in this group yet. Click "Add Lesson" to start building content.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No content structure</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding a new lesson group.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.groups.create', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add New Group
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

</body>
</html>