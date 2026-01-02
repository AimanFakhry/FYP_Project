<!-- Admin Navbar with Vanilla JavaScript -->
<nav class="bg-white shadow-md fixed w-full top-0 z-20">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Hamburger Menu Button (Mobile Only) -->
                <button id="sidebar-open-btn" class="text-gray-600 focus:outline-none mr-4 md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Home Link -->
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800 mr-8">
                    Home
                </a>

                <!-- Desktop Navigation Links (Visible) -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}">
                        Dashboard
                    </a>
                    <!-- FIX: Ensure this link works -->
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : '' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.courses.*') ? 'text-indigo-600' : '' }}">
                        Courses
                    </a>
                    <a href="{{ route('admin.achievements.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.achievements.*') ? 'text-indigo-600' : '' }}">
                        Achievements
                    </a>
                </div>
            </div>

            <div class="flex items-center">
                <!-- User Profile Dropdown -->
                @auth
                    <div class="relative ml-6">
                        <button id="profile-menu-btn" class="relative z-10 block h-10 w-10 rounded-full overflow-hidden border-2 border-gray-300 focus:outline-none focus:border-indigo-500 hover:border-indigo-400 transition-colors">
                            <!-- Placeholder for Profile Picture -->
                            <div class="h-full w-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-20 py-1 border border-gray-100">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-bold">Admin</span>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Profile</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden transition-opacity duration-300 md:hidden"></div>

<!-- Sidebar (Mobile) -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    
    <!-- Sidebar Header with Close Button -->
    <div class="p-6 border-b border-gray-800 flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">Admin Menu</h2>
        <button id="sidebar-close-btn" class="text-gray-400 hover:text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : '' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A10.99 10.99 0 002.45 12a10.99 10.99 0 009.548-5.197m0 0A10.99 10.99 0 0021.548 12a10.99 10.99 0 00-9.548 5.197M15 21a6 6 0 00-9-5.197"/></svg>
            Users
        </a>
        <a href="{{ route('admin.courses.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.courses.*') ? 'bg-gray-800 text-white' : '' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-9.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-9.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222 4 2.222V20M12 14.75L4 9.5 12 5l8 4.5-8 5.25z"></path></svg>
            Courses
        </a>
        <a href="{{ route('admin.achievements.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.achievements.*') ? 'bg-gray-800 text-white' : '' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Achievements
        </a>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('sidebar-open-btn');
        const closeBtn = document.getElementById('sidebar-close-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Dropdown logic
        const profileBtn = document.getElementById('profile-menu-btn');
        const profileMenu = document.getElementById('profile-menu');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        }
    });
</script>