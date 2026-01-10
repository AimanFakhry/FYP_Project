<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
    </style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-700">My Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="md:grid md:grid-cols-3 md:gap-6">
                
                <!-- Left Column: Profile Card -->
                <div class="md:col-span-1">
                    
                    <!-- Main Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <div class="relative mx-auto h-24 w-24 mb-4">
                                <div class="rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-4xl font-bold h-full w-full border-4 border-white shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <!-- Green status dot removed to prevent confusion -->
                            </div>
                            
                            <h3 class="text-lg font-bold leading-6 text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ Auth::user()->email }}</p>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                System Administrator
                            </span>
                        </div>
                        
                        <div class="border-t border-gray-100 bg-gray-50 px-4 py-4 sm:px-6">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">System Access</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Role ID</dt>
                                    <dd class="font-medium text-gray-900">ADMIN_{{ str_pad(Auth::user()->id, 3, '0', STR_PAD_LEFT) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Joined</dt>
                                    <dd class="font-medium text-gray-900">{{ Auth::user()->created_at->format('M d, Y') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Status</dt>
                                    <dd class="font-medium text-green-600">Active</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Security Log / Recent Activity -->
                    <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-700">Recent Login Activity</h3>
                        </div>
                        <div class="px-4 py-4">
                            <ul class="space-y-4">
                                @forelse($sessions as $session)
                                    <li class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-2 w-2 mt-2 rounded-full bg-green-500"></div>
                                        </div>
                                        <div class="flex-1 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ $session->ip_address }}
                                                </h3>
                                                <p class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                                </p>
                                            </div>
                                            <p class="text-xs text-gray-500 truncate" title="{{ $session->user_agent }}">
                                                {{ Str::limit($session->user_agent, 25) }}
                                            </p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500 text-center py-2">No recent activity found.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Edit Form -->
                <div class="mt-6 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Account Settings</h3>
                            <p class="mt-1 text-sm text-gray-500">Update your profile information and manage your security.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf
                                <!-- Route::post('/profile/update') handles this -->
                                
                                <!-- Basic Info Section -->
                                <div class="mb-8">
                                    <h4 class="text-sm font-medium text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">General Information</h4>
                                    
                                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Display Name</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required
                                                       class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required
                                                       class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Section -->
                                <div>
                                    <h4 class="text-sm font-medium text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">Security</h4>
                                    
                                    <div class="space-y-4">
                                        <p class="text-xs text-gray-500">Leave the password fields blank if you do not wish to change it.</p>
                                        
                                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                            <div class="sm:col-span-6">
                                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                                <input type="password" name="current_password" id="current_password"
                                                       class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border">
                                                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                                <input type="password" name="new_password" id="password"
                                                       class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border">
                                                @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                                       class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>