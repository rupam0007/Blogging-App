@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Display Success Message from Session --}}
    @if (session('success'))
        <div class="bg-green-600/20 border border-green-500 text-green-300 p-4 rounded-xl mb-6 flex items-center space-x-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">
            Welcome back, <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">{{ Auth::user()->name }}!</span>
        </h1>
        <p class="text-gray-400 text-lg">Here's what's happening on your profile</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Posts Count -->
        <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.edit') }}" class="bg-gradient-to-br from-purple-600/20 to-purple-900/20 border border-purple-500/30 rounded-2xl p-6 hover:border-purple-400/50 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-purple-500/20 rounded-xl group-hover:bg-purple-500/30 transition-all">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white mb-1">{{ Auth::user()->posts()->count() }}</p>
            <p class="text-purple-300 text-sm font-medium">Posts Created</p>
        </a>

        <!-- Followers Count -->
        <a href="{{ Auth::user()->username ? route('users.followers', Auth::user()->username) : '#' }}" class="bg-gradient-to-br from-pink-600/20 to-pink-900/20 border border-pink-500/30 rounded-2xl p-6 hover:border-pink-400/50 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-pink-500/20 rounded-xl group-hover:bg-pink-500/30 transition-all">
                    <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white mb-1">{{ Auth::user()->followers()->count() }}</p>
            <p class="text-pink-300 text-sm font-medium">Followers</p>
        </a>

        <!-- Following Count -->
        <a href="{{ Auth::user()->username ? route('users.following', Auth::user()->username) : '#' }}" class="bg-gradient-to-br from-blue-600/20 to-blue-900/20 border border-blue-500/30 rounded-2xl p-6 hover:border-blue-400/50 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-blue-500/20 rounded-xl group-hover:bg-blue-500/30 transition-all">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white mb-1">{{ Auth::user()->following()->count() }}</p>
            <p class="text-blue-300 text-sm font-medium">Following</p>
        </a>

        <!-- Notifications Count -->
        <a href="{{ route('notifications.index') }}" class="bg-gradient-to-br from-green-600/20 to-green-900/20 border border-green-500/30 rounded-2xl p-6 hover:border-green-400/50 transition-all duration-300 group relative">
            @if(Auth::user()->notifications()->whereNull('read_at')->count() > 0)
                <span class="absolute top-4 right-4 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            @endif
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-green-500/20 rounded-xl group-hover:bg-green-500/30 transition-all">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white mb-1">{{ Auth::user()->notifications()->whereNull('read_at')->count() }}</p>
            <p class="text-green-300 text-sm font-medium">Unread Notifications</p>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('posts.create') }}" class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-purple-500/50 transition-all duration-300 group">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-purple-400 transition-colors">Create Post</h3>
                    <p class="text-gray-400 text-sm">Share your thoughts</p>
                </div>
            </div>
        </a>

        <a href="{{ route('stories.create') }}" class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-purple-500/50 transition-all duration-300 group">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-purple-400 transition-colors">Create Story</h3>
                    <p class="text-gray-400 text-sm">Share a moment</p>
                </div>
            </div>
        </a>

        <a href="{{ route('explore') }}" class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-purple-500/50 transition-all duration-300 group">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-purple-400 transition-colors">Explore</h3>
                    <p class="text-gray-400 text-sm">Discover content</p>
                </div>
            </div>
        </a>

        <a href="{{ Auth::user()->username ? route('profile.show', Auth::user()->username) : route('profile.edit') }}" class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-purple-500/50 transition-all duration-300 group">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-purple-400 transition-colors">My Profile</h3>
                    <p class="text-gray-400 text-sm">View your profile</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity & Trending Posts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-1 bg-gray-800 border border-gray-700 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Recent Activity</h2>
                <a href="{{ route('notifications.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">View all</a>
            </div>

            <div class="space-y-4">
                @forelse(Auth::user()->notifications()->latest()->limit(5)->get() as $notification)
                    @php
                        $notifUrl = '#';
                        if ($notification->type === 'follow') {
                            $notifUrl = route('profile.show', $notification->fromUser->username ?? 'user');
                        } elseif (in_array($notification->type, ['comment', 'reaction', 'mention']) && $notification->notifiable_type === 'App\\Models\\Post') {
                            $notifUrl = route('posts.show', $notification->notifiable_id ?? 0);
                        }
                    @endphp
                    <a href="{{ $notifUrl }}" class="block p-3 rounded-lg hover:bg-gray-700/50 transition-all {{ $notification->read_at ? 'opacity-60' : '' }}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'follow')
                                    <div class="p-2 bg-purple-500/20 rounded-lg">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @elseif($notification->type === 'reaction')
                                    <div class="p-2 bg-pink-500/20 rounded-lg">
                                        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="p-2 bg-blue-500/20 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-300 line-clamp-2">{{ $notification->content ?? 'New notification' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Trending Posts -->
        <div class="lg:col-span-2 bg-gray-800 border border-gray-700 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Trending Posts</h2>
                <a href="{{ route('explore') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">Explore more</a>
            </div>

            @php
                $trendingPosts = \App\Models\Post::with('user')
                    ->published()
                    ->withCount(['reactions', 'comments'])
                    ->where('created_at', '>=', now()->subDays(7))
                    ->orderByDesc('reactions_count')
                    ->limit(3)
                    ->get();
            @endphp

            <div class="space-y-4">
                @forelse($trendingPosts as $post)
                    <div class="block p-4 rounded-lg hover:bg-gray-700/50 transition-all border border-gray-700/50 hover:border-purple-500/50">
                        <div class="flex items-start space-x-4">
                            @if($post->image_path)
                                <div class="relative group cursor-pointer flex-shrink-0" onclick="openImageModal('{{ asset('storage/' . $post->image_path) }}', '{{ $post->title }}')">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-20 h-20 rounded-lg object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('posts.show', $post->id) }}">
                                    <h3 class="text-white font-semibold mb-1 line-clamp-2 hover:text-purple-400 transition-colors">{{ $post->title }}</h3>
                                </a>
                                <p class="text-gray-400 text-sm line-clamp-2 mb-2">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <div class="flex items-center space-x-1">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-4 h-4 rounded-full">
                                        @endif
                                        <span>{{ $post->user->name }}</span>
                                    </div>
                                    <span>{{ $post->reactions_count }} reactions</span>
                                    <span>{{ $post->comments_count }} comments</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <p class="text-gray-500">No trending posts yet</p>
                        <p class="text-gray-600 text-sm mt-1">Start creating content to see trends</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Additional Navigation Links -->
    <div class="mt-8 text-center">
        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-purple-500/50">
            Go to Dashboard 📝
        </a>
        <a href="{{ route('blogs.index') }}" class="inline-block ml-4 px-8 py-4 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-600 transition-all duration-300">
            View Public Blogs 📰
        </a>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <div class="max-w-7xl max-h-full" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-screen object-contain rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

<script>
    function openImageModal(src, caption) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = src;
        document.getElementById('modalCaption').textContent = caption;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeImageModal();
    });
</script>
@endsection