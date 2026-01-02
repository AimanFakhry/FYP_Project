<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Profile - YourApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="antialiased bg-gray-100">

    @include('partials.navbar')

    <main class="container mx-auto px-4 py-12 mt-[68px]">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">
                Your Profile
            </h1>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Joined</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
