<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : true }" 
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Blog')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.css">
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'system-ui', 'sans-serif'],
                        'display': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        html.has-scroll-smooth {
            overflow: hidden;
        }
        html.has-scroll-dragging {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .has-scroll-smooth body {
            overflow: hidden;
        }
        .has-scroll-smooth [data-scroll-container] {
            min-height: 100vh;
        }
        [data-scroll-container] {
            min-height: 100vh;
        }
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .loader {
            width: 60px;
            height: 60px;
            position: relative;
        }
        .loader-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 4px solid transparent;
            border-top-color: #9333ea;
            border-radius: 50%;
            animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }
        .loader-ring:nth-child(2) {
            border-top-color: #ec4899;
            animation-delay: -0.4s;
        }
        .loader-ring:nth-child(3) {
            border-top-color: #8b5cf6;
            animation-delay: -0.8s;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loader-text {
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'liga';
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .light .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-icon-wrapper {
            position: relative;
            display: inline-block;
        }
        .nav-tooltip {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%) translateY(5px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }
        .nav-tooltip::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid #667eea;
        }
        .nav-icon-wrapper:hover .nav-tooltip {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .nav-icon-wrapper svg {
            transition: all 0.3s ease;
        }
        .nav-icon-wrapper:hover svg {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(102, 126, 234, 0.5));
        }
        /* Theme toggle button styles */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .light .theme-toggle {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }
        .theme-toggle-circle {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark .theme-toggle-circle {
            transform: translateX(30px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div>
            <div class="loader">
                <div class="loader-ring"></div>
                <div class="loader-ring"></div>
                <div class="loader-ring"></div>
            </div>
            <div class="loader-text">Loading...</div>
        </div>
    </div>

    <div data-scroll-container>
    <nav class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 fixed w-full z-50 top-0 backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95" x-data="{ mobileMenuOpen: false }" data-scroll data-scroll-sticky data-scroll-target="#main-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
                        <span class="material-symbols-outlined" x-show="mobileMenuOpen">close</span>
                    </button>

                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-500" style="font-size: 32px;">article</span>
                        <span class="text-xl font-bold font-display gradient-text hidden sm:block">Smart Blog</span>
                    </a>

                    <div class="hidden md:flex space-x-6">
                        @auth
                        <div class="nav-icon-wrapper">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                                <span class="material-symbols-outlined">home</span>
                            </a>
                            <span class="nav-tooltip">Home Feed </span>
                        </div>
                        <div class="nav-icon-wrapper">
                            <a href="{{ route('stories.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                                <span class="material-symbols-outlined">control_point_duplicate</span>
                            </a>
                            <span class="nav-tooltip">Stories </span>
                        </div>
                        @endauth
                        <div class="nav-icon-wrapper">
                            <a href="{{ route('blogs.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                                <span class="material-symbols-outlined">library_books</span>
                            </a>
                            <span class="nav-tooltip">Blog Posts </span>
                        </div>
                        @auth
                        <div class="nav-icon-wrapper">
                            <a href="{{ route('bookmarks.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                                <span class="material-symbols-outlined">bookmarks</span>
                            </a>
                            <span class="nav-tooltip">Bookmarks </span>
                        </div>
                        @endauth
                    </div>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Theme Toggle Button -->
                    <button @click="darkMode = !darkMode" class="theme-toggle scale-90 sm:scale-100" title="Toggle Theme">
                        <div class="theme-toggle-circle">
                            <span x-show="!darkMode" class="material-symbols-outlined text-yellow-500" style="font-size: 16px;">light_mode</span>
                            <span x-show="darkMode" class="material-symbols-outlined text-purple-600" style="font-size: 16px;">dark_mode</span>
                        </div>
                    </button>

                    <div class="nav-icon-wrapper hidden md:block">
                        <a href="{{ route('search') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                            <span class="material-symbols-outlined">search</span>
                        </a>
                        <span class="nav-tooltip">Search </span>
                    </div>

                    @auth
                    <div class="relative nav-icon-wrapper" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" id="notif-count"></span>
                        </button>
                        <span class="nav-tooltip">Notifications </span>

                        <div x-show="open" @click.away="open = false" x-init="$watch('open', value => value && loadNotificationsList())" class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <h3 class="font-bold text-gray-900 dark:text-white">Notifications</h3>
                                <a href="{{ route('notifications.index') }}" class="text-xs text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">View All</a>
                            </div>
                            <div id="notifications-list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-8 h-8 mx-auto mb-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-icon-wrapper">
                        <a href="{{ route('posts.create') }}" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-white transition block">
                            <span class="material-symbols-outlined">add_circle</span>
                        </a>
                        <span class="nav-tooltip">Create Post </span>
                    </div>

                    <div class="relative hidden sm:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 hover:opacity-80 transition">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-8 h-8 rounded-full ring-2 ring-transparent hover:ring-purple-500 transition">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold ring-2 ring-transparent hover:ring-purple-500 transition">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700">
                            <a href="{{ route('profile.show', Auth::user()->username ?? Auth::id()) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg text-gray-700 dark:text-gray-200">
                                <span class="flex items-center space-x-2">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">person</span>
                                    <span>Profile</span>
                                </span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <span class="flex items-center space-x-2">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
                                    <span>Settings</span>
                                </span>
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-purple-600 dark:text-purple-400">
                                    <span class="flex items-center space-x-2">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">admin_panel_settings</span>
                                        <span>Admin Panel</span>
                                    </span>
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-200 dark:border-gray-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400 rounded-b-lg flex items-center space-x-2">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-semibold transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-semibold transition-all shadow-sm hover:shadow-md">
                            Sign Up
                        </a>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="md:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900"
                 @click.away="mobileMenuOpen = false">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">home</span>
                        <span>Home Feed</span>
                    </a>
                    <a href="{{ route('stories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">control_point_duplicate</span>
                        <span>Stories</span>
                    </a>
                    @endauth
                    <a href="{{ route('blogs.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">library_books</span>
                        <span>Blog Posts</span>
                    </a>
                    @auth
                    <a href="{{ route('bookmarks.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">bookmarks</span>
                        <span>Bookmarks</span>
                    </a>
                    <a href="{{ route('search') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">search</span>
                        <span>Search</span>
                    </a>
                    <a href="{{ route('explore') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">explore</span>
                        <span>Explore</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">notifications</span>
                        <span>Notifications</span>
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" id="mobile-notif-count"></span>
                    </a>
                    <a href="{{ route('posts.create') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span>Create Post</span>
                    </a>

                    <!-- Mobile Profile Menu -->
                    <div class="border-t border-gray-200 dark:border-gray-800 pt-3 mt-2">
                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::id()) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-8 h-8 rounded-full">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400 transition">
                            <span class="material-symbols-outlined">settings</span>
                            <span>Settings</span>
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-purple-600 dark:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                <span class="material-symbols-outlined">admin_panel_settings</span>
                                <span>Admin Panel</span>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                <span class="material-symbols-outlined">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-16 min-h-screen bg-gray-50 dark:bg-gray-950" id="main-container" data-scroll-section>
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Initialize Locomotive Scroll
        let scroll;
        document.addEventListener('DOMContentLoaded', () => {
            scroll = new LocomotiveScroll({
                el: document.querySelector('[data-scroll-container]'),
                smooth: true,
                smoothMobile: true,
                resetNativeScroll: true,
                smartphone: {
                    smooth: true
                },
                tablet: {
                    smooth: true
                }
            });
        });

        // Show loader function
        function showLoader(message = 'Loading...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = overlay.querySelector('.loader-text');
            if (text) text.textContent = message;
            overlay.classList.add('active');
        }

        // Hide loader function
        function hideLoader() {
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.remove('active');
        }

        // Intercept all form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            
            // Don't show loader for logout forms
            if (form.action.includes('logout')) {
                showLoader('Logging out...');
                return;
            }

            // Show appropriate loading messages
            if (form.action.includes('login')) {
                e.preventDefault();
                showLoader('Logging in...');
                setTimeout(() => form.submit(), 300);
            } else if (form.action.includes('register')) {
                e.preventDefault();
                showLoader('Creating account...');
                setTimeout(() => form.submit(), 300);
            } else if (form.action.includes('posts') || form.action.includes('dashboard')) {
                e.preventDefault();
                showLoader('Saving post...');
                setTimeout(() => form.submit(), 300);
            } else if (form.action.includes('comments')) {
                e.preventDefault();
                showLoader('Posting comment...');
                setTimeout(() => form.submit(), 300);
            } else if (form.action.includes('stories')) {
                e.preventDefault();
                showLoader('Uploading story...');
                setTimeout(() => form.submit(), 300);
            } else if (form.action.includes('profile')) {
                e.preventDefault();
                showLoader('Updating profile...');
                setTimeout(() => form.submit(), 300);
            } else {
                e.preventDefault();
                showLoader('Processing...');
                setTimeout(() => form.submit(), 300);
            }
        });

        // Show loader on link clicks that cause navigation
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('#') && !link.target && 
                !link.hasAttribute('download') && !link.classList.contains('no-loader')) {
                // Don't show loader for same page navigation
                if (link.href !== window.location.href) {
                    showLoader('Loading page...');
                }
            }
        });

        // Hide loader when page loads
        window.addEventListener('load', () => {
            hideLoader();
        });

        // Hide loader on back/forward navigation
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hideLoader();
            }
        });

        // Prevent form resubmission dialog on mobile
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            hideLoader();
        }

        // Prevent "Confirm Form Resubmission" dialog
        window.addEventListener('beforeunload', function(e) {
            // Only show loader, don't prevent unload
            showLoader('Loading...');
        });
    </script>
    <script>
        async function loadNotifications() {
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}');
                const data = await response.json();
                const badge = document.getElementById('notif-count');
                const mobileBadge = document.getElementById('mobile-notif-count');
                
                if (badge) {
                    badge.textContent = data.count;
                    if(data.count === 0) {
                        badge.style.display = 'none';
                    } else {
                        badge.style.display = 'flex';
                    }
                }
                
                if (mobileBadge) {
                    mobileBadge.textContent = data.count;
                    if(data.count === 0) {
                        mobileBadge.style.display = 'none';
                    } else {
                        mobileBadge.style.display = 'flex';
                    }
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        async function loadNotificationsList() {
            try {
                const response = await fetch('{{ route("notifications.recent") }}');
                const data = await response.json();
                const container = document.getElementById('notifications-list');
                
                if (data.notifications.length === 0) {
                    container.innerHTML = `
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No notifications yet</p>
                        </div>
                    `;
                    return;
                }
                
                container.innerHTML = data.notifications.map(notif => {
                    const isUnread = !notif.read_at;
                    const bgClass = isUnread ? 'bg-purple-50 dark:bg-gray-700/50' : 'bg-transparent';
                    const icon = getNotificationIcon(notif.type);
                    const timeAgo = formatTimeAgo(notif.created_at);
                    const avatarUrl = notif.from_user?.avatar 
                        ? `{{ asset('storage') }}/${notif.from_user.avatar}` 
                        : null;
                    const initial = notif.from_user?.name?.charAt(0) || '?';
                    
                    return `
                        <div class="${bgClass} hover:bg-gray-100 dark:hover:bg-gray-700 p-4 transition cursor-pointer" onclick="markAsReadAndRedirect(${notif.id}, '${getNotificationUrl(notif)}')">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    ${avatarUrl 
                                        ? `<img src="${avatarUrl}" class="w-10 h-10 rounded-full">`
                                        : `<div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold">${initial}</div>`
                                    }
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">${notif.content}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">${timeAgo}</span>
                                        ${isUnread ? '<span class="w-2 h-2 bg-purple-600 dark:bg-purple-500 rounded-full"></span>' : ''}
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                    ${icon}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                
            } catch (error) {
                console.error('Error loading notifications list:', error);
                document.getElementById('notifications-list').innerHTML = `
                    <div class="p-4 text-center text-red-600 dark:text-red-400">
                        <p>Failed to load notifications</p>
                    </div>
                `;
            }
        }

        function getNotificationIcon(type) {
            const icons = {
                'follow': '<span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">person_add</span>',
                'unfollow': '<span class="material-symbols-outlined text-gray-500" style="font-size: 20px;">person_remove</span>',
                'reaction': '<span class="material-symbols-outlined text-red-500" style="font-size: 20px;">favorite</span>',
                'comment': '<span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">comment</span>',
                'mention': '<span class="material-symbols-outlined text-yellow-500" style="font-size: 20px;">alternate_email</span>'
            };
            return icons[type] || icons['follow'];
        }

        function getNotificationUrl(notif) {
            if (notif.type === 'follow' || notif.type === 'unfollow') {
                return `/profile/${notif.from_user?.username || notif.from_user?.id}`;
            }
            if (notif.type === 'reaction' || notif.type === 'comment') {
                return `/blogs/${notif.notifiable_id}`;
            }
            if (notif.type === 'mention') {
                return `/blogs/${notif.notifiable_id}`;
            }
            return '/notifications';
        }

        function formatTimeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };
            
            for (const [unit, secondsInUnit] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / secondsInUnit);
                if (interval >= 1) {
                    return `${interval} ${unit}${interval > 1 ? 's' : ''} ago`;
                }
            }
            return 'Just now';
        }

        async function markAsReadAndRedirect(notifId, url) {
            try {
                await fetch(`/notifications/${notifId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                window.location.href = url;
            } catch (error) {
                console.error('Error marking notification as read:', error);
                window.location.href = url;
            }
        }

        loadNotifications();
        setInterval(loadNotifications, 30000);
    </script>
    @stack('scripts')
</body>
</html>
