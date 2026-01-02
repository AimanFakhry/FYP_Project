<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }</style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Curriculum Management</h1>
                <p class="mt-1 text-sm text-gray-500">Manage course content, structure, and lessons.</p>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                        
                        <!-- Card Header / Banner -->
                        <div class="h-24 {{ $course->color_class ?? 'bg-gray-600' }} relative">
                            <div class="absolute -bottom-8 left-6">
                                <div class="h-16 w-16 rounded-xl bg-white p-2 shadow-lg flex items-center justify-center">
                                    <div class="h-full w-full {{ $course->color_class ?? 'bg-gray-600' }} rounded-lg flex items-center justify-center text-white">
                                        {!! $course->icon_svg !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="pt-10 px-6 pb-6 flex-grow flex flex-col">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $course->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2" title="{{ $course->description }}">
                                    {{ $course->description }}
                                </p>
                            </div>

                            <!-- Stats Row -->
                            <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mb-6 mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    {{ $course->lessonGroups()->count() }} Parts
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    {{ $course->lessons()->count() }} Lessons
                                </div>
                            </div>

                            <!-- Action -->
                            <a href="{{ route('admin.courses.show', $course) }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Manage Content
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No courses available</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by seeding the database.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

</body>
</html>