<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses - YourApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="antialiased bg-gray-50">

    @include('partials.navbar')

    <main class="min-h-screen flex items-center justify-center pt-[68px]">
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Our Courses</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Course Card 1: C++ -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1579403124614-197f69d8187b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80" alt="C++ code on a screen">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">C++ Programming</h3>
                        <p class="text-gray-600 mb-4">Master the fundamentals and advanced topics of C++, from basic syntax to complex data structures.</p>
                        @auth
                            <a href="{{ route('courses.show', ['course' => 'cpp']) }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @endauth
                    </div>
                </div>

                <!-- Course Card 2: PHP -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1599507593499-a3f7d7d97667?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="PHP code on a screen">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Modern PHP</h3>
                        <p class="text-gray-600 mb-4">Dive into server-side scripting with PHP. Learn to build dynamic web applications with Laravel.</p>
                        @auth
                            <a href="{{ route('courses.show', ['course' => 'php']) }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @endauth
                    </div>
                </div>

                <!-- Course Card 3: JavaScript -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="JavaScript code on a screen">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">JavaScript Mastery</h3>
                        <p class="text-gray-600 mb-4">Become proficient in the language of the web. Explore everything from vanilla JS to modern frameworks.</p>
                        @auth
                            <a href="{{ route('courses.show', ['course' => 'javascript']) }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">Start Learning</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>

