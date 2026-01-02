<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Add Group - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }</style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Add New Group</h1>
                        <p class="text-xs text-gray-500 mt-1">Course: <span class="font-medium text-gray-700">{{ $course->name }}</span></p>
                    </div>
                    <a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.groups.store', $course) }}" method="POST">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Group Title</label>
                            <input type="text" name="title" id="title" required placeholder="e.g. Part 1: Fundamentals"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Order -->
                        <div class="mb-6">
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order (Part Number)</label>
                            <input type="number" name="order" id="order" required value="{{ $course->lessonGroups->count() + 1 }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                            <p class="text-xs text-gray-500 mt-1">Determines the sequence of parts in the learning path.</p>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>