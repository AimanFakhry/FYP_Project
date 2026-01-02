<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Add Achievement - Admin</title>
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
                        <h1 class="text-lg font-bold text-gray-900">Create Achievement</h1>
                        <p class="text-xs text-gray-500 mt-1">Define a new reward for users.</p>
                    </div>
                    <a href="{{ route('admin.achievements.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.achievements.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Achievement Name</label>
                            <input type="text" name="name" id="name" required placeholder="e.g. Early Bird"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <div class="mb-5">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" required placeholder="e.g. Complete the first lesson before 9 AM."
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border"></textarea>
                        </div>

                        <div class="mb-6">
                            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Linked Course (Optional)</label>
                            <select name="course_id" id="course_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-white">
                                <option value="">-- General Achievement (Default Trophy Icon) --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} (Uses Course Icon)</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Linking to a course will use that course's SVG icon for this achievement.</p>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Achievement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>