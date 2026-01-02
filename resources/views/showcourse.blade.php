<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course: {{ Str::upper($courseName) }} - YourApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="antialiased bg-gray-50">

    @include('partials.navbar')

    <main class="container mx-auto px-4 py-12 mt-[68px]">
        <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">
            Welcome to the {{ Str::title($courseName) }} Course
        </h1>
        <div class="bg-white p-8 rounded-lg shadow-md">
            <p class="text-lg text-gray-700">Course content for {{ $courseName }} will go here.</p>
        </div>
    </main>

</body>
</html>
